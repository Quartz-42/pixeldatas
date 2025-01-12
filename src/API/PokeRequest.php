<?php

namespace App\API;

use App\Entity\Pokemon;
use App\Entity\Pokevolution;
use App\Entity\Talent;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokeRequest
{
    private $client;
    private $cache;
    private EntityManagerInterface $em;

    public function __construct(
        HttpClientInterface $client,
        CacheInterface $cache,
        EntityManagerInterface $em
    ) {
        $this->client = $client;
        $this->cache = $cache;
        $this->em = $em;
    }

    public function getResponse()
    {
        $response = $this->client->request(
            'GET',
            'https://tyradex.vercel.app/api/v1/pokemon'
        );

        // Gestion d'erreur pour la requête HTTP
        if (200 !== $response->getStatusCode()) {
            throw new \Exception('Erreur lors de la récupération des données des Pokémon. Veuillez réessayer dans quelques instants...');
        }

        return $response;
    }

    public function getRandomPokemon(int $count): array
    {
        return $this->cache->get('randomPoke', function (ItemInterface $item) use ($count): array {
            $item->expiresAfter(86400);

            $response = $this->getResponse();
            $content = $response->toArray();

            shuffle($content);

            return array_slice($content, 0, $count);
        });
    }

    public function getAllPokemons(): array
    {
        return $this->cache->get('allPoke', function (ItemInterface $item): array {
            $item->expiresAfter(300);

            $response = $this->getResponse();

            $content = $response->toArray();

            return $content;
        });
    }

    public function getObjPokemons(): array
    {
        return $this->cache->get('allPoke', function (ItemInterface $item): array {
            $item->expiresAfter(300);

            $response = $this->getResponse();

            $content = $response->toArray();

            $pokemons = array_slice($content, 0, 10);
            $pokemonEntities = [];

            // Récupérer tous les talents existants dans la base de données
            $existingTalents = $this->em->getRepository(Talent::class)->findAll();
            $existingTalentNames = [];
            foreach ($existingTalents as $talent) {
                $existingTalentNames[$talent->getName()] = $talent;
            }

            // Récupérer tous les types existants en une seule requête
            $existingType = $this->em->getRepository(Type::class)->findAll();
            $existingTypeNames = [];
            foreach ($existingType as $type) {
                $existingTypeNames[$type->getName()] = $type;
            }

            foreach ($pokemons as $pokemon) {
                if ($pokemon['pokedex_id'] != null) {

                    // Contrôle pour ne pas faire les choses deux fois
                    $pokemonExistant = $this->em->getRepository(Pokemon::class)->findOneBy(['pokedexId' => $pokemon['pokedex_id']]);
                    if ($pokemonExistant) {
                        $pokemonEntities[] = $pokemonExistant;
                        continue;
                    }

                    $poke = new Pokemon();
                    $poke->setPokedexId($pokemon['pokedex_id']);
                    $poke->setGeneration($pokemon['generation']);
                    $poke->setCategory($pokemon['category']);

                    // On set les images
                    $poke->setSpriteRegular($pokemon['sprites']['regular']);
                    if (isset($pokemon['sprites']['shiny'])) {
                        $poke->setSpriteShiny($pokemon['sprites']['shiny']);
                    }
                    if (isset($pokemon['sprites']['gmax']['regular'])) {
                        $poke->setSpriteGmax($pokemon['sprites']['gmax']['regular']);
                    }
                    if (isset($pokemon['sprites']['gmax']['regular'])) {
                        $poke->setSpriteGmaxShiny($pokemon['sprites']['gmax']['shiny']);
                    }

                    $poke->setName($pokemon['name']['fr']);
                    $poke->setHp($pokemon['stats']['hp']);
                    $poke->setAtk($pokemon['stats']['atk']);
                    $poke->setDef($pokemon['stats']['def']);
                    $poke->setSpeAtk($pokemon['stats']['spe_atk']);
                    $poke->setSpeDef($pokemon['stats']['spe_def']);
                    $poke->setVit($pokemon['stats']['vit']);
                    $poke->setHeight($pokemon['height']);
                    $poke->setWeight($pokemon['weight']);

                    // On set les talents
                    if (!empty($pokemon['talents'])) {
                        foreach ($pokemon['talents'] as $talentData) {
                            if (isset($existingTalentNames[$talentData['name']])) {
                                $poke->addTalent($existingTalentNames[$talentData['name']]);
                            } else {
                                // Si le talent n'existe pas, on le crée et on le persiste
                                $talent = new Talent();
                                $talent->setName($talentData['name']);
                                $this->em->persist($talent);
                                $existingTalentNames[$talentData['name']] = $talent;
                                $poke->addTalent($talent);
                            }
                        }
                    }

                    // On set les types
                    if (!empty($pokemon['types'])) {
                        foreach ($pokemon['types'] as $typeData) {
                            if (isset($existingTypeNames[$typeData['name']])) {
                                $poke->addType($existingTypeNames[$typeData['name']]);
                            } else {
                                $type = new Type();
                                $type->setName($typeData['name']);
                                $type->setImage($typeData['image']);
                                $this->em->persist($type);
                                $existingTypeNames[$typeData['name']] = $type;
                                $poke->addType($type);
                            }
                        }
                    }

                    $this->em->persist($poke);
                    $pokemonEntities[] = $poke;
                }
            }
            $this->em->flush();

            // Mettre à jour les évolutions après que tous les Pokémon ont été enregistrés
            foreach ($pokemonEntities as $poke) {
                $pokemon = $content[array_search($poke->getPokedexId(), array_column($content, 'pokedex_id'))];

                // Créer une seule instance de Pokevolution pour chaque Pokémon
                $evolution = new Pokevolution();
                $evolution->setPokemon($poke);

                // On set les évolutions précédentes
                if (!empty($pokemon['evolution']['pre'])) {
                    $preEvolutions = $pokemon['evolution']['pre'];
                    if (isset($preEvolutions[0])) {
                        $pokemonPre1 = $this->em->getRepository(Pokemon::class)->findOneBy(['pokedexId' => $preEvolutions[0]['pokedex_id']]);
                        $evolution->setPreEvolution1($pokemonPre1);
                    }
                    if (isset($preEvolutions[1])) {
                        $pokemonPre2 = $this->em->getRepository(Pokemon::class)->findOneBy(['pokedexId' => $preEvolutions[1]['pokedex_id']]);
                        $evolution->setPreEvolution2($pokemonPre2);
                    }
                }

                // On set les évolutions suivantes
                if (!empty($pokemon['evolution']['next'])) {
                    $nextEvolutions = $pokemon['evolution']['next'];
                    if (isset($nextEvolutions[0])) {
                        $pokemonNext1 = $this->em->getRepository(Pokemon::class)->findOneBy(['pokedexId' => $nextEvolutions[0]['pokedex_id']]);
                        $evolution->setNextEvolution1($pokemonNext1);
                    }
                    if (isset($nextEvolutions[1])) {
                        $pokemonNext2 = $this->em->getRepository(Pokemon::class)->findOneBy(['pokedexId' => $nextEvolutions[1]['pokedex_id']]);
                        $evolution->setNextEvolution2($pokemonNext2);
                    }
                }

                // On set les méga-évolutions
                if (!empty($pokemon['evolution']['mega'])) {
                    $evolution->setMegaEvolution(true);
                }

                $this->em->persist($evolution);
            }

            $this->em->flush();

            return $pokemonEntities;
        });
    }

    public function getPokemonByType($type): array
    {
        return $this->cache->get('pokeByType', function (ItemInterface $item) use ($type): array {
            $item->expiresAfter(300);

            $response = $this->getResponse();

            $content = $response->toArray();

            $pokemonsType = [];

            foreach ($content as $pokemon) {
                if (isset($pokemon['types'])) {
                    foreach ($pokemon['types'] as $types) {
                        if ($types['name'] === $type) {
                            $pokemonsType[] = $pokemon;
                            break;
                        }
                    }
                }
            }

            return $pokemonsType;
        });
    }

    public function getPokemonByName($name): array
    {
        return $this->cache->get('pokeByName', function (ItemInterface $item) use ($name): array {
            $item->expiresAfter(300);

            $response = $this->getResponse();

            $content = $response->toArray();

            foreach ($content as $pokemon) {
                if ($pokemon['name']['fr'] == $name) {
                    return $pokemon;
                }
            }
        });
    }

    public function getPokemonByGeneration($generation): array
    {
        return $this->cache->get('pokeByGen', function (ItemInterface $item) use ($generation): array {
            $item->expiresAfter(300);

            $response = $this->getResponse();

            $content = $response->toArray();

            $pokemonsGeneration = [];

            foreach ($content as $pokemon) {
                if (isset($pokemon['generation']) && $pokemon['generation'] == $generation) {
                    $pokemonsGeneration[] = $pokemon;
                }
            }

            return $pokemonsGeneration;
        });
    }

    public function getPokemonStats(int $count): array
    {
        return $this->cache->get('pokeStats', function (ItemInterface $item) use ($count): array {
            $item->expiresAfter(3600);

            // Premier appel API pour obtenir la liste des Pokémon
            $response = $this->client->request('GET', 'https://tyradex.vercel.app/api/v1/pokemon');
            $content = $response->toArray();
            $pokemonStats = [];

            foreach ($content as $pokemon) {
                // Calculer la moyenne des statistiques
                if (isset($pokemon['stats']) && is_array($pokemon['stats'])) {
                    $stats = $pokemon['stats'];
                    $totalStats = array_sum($stats);
                    $averageStats = ceil($totalStats / count($stats));

                    $imageUrl = $pokemon['sprites']['regular'] ?? null;

                    $pokemonStats[] = [
                        'name' => $pokemon['name']['fr'],
                        'average_stats' => $averageStats,
                        'image' => $imageUrl,
                    ];
                }
            }

            // Trier les Pokémon par moyenne des statistiques
            usort($pokemonStats, function ($a, $b) {
                return $b['average_stats'] <=> $a['average_stats'];
            });

            return array_slice($pokemonStats, 0, $count);
        });
    }
}

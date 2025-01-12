<?php

namespace App\API;

use App\Entity\Pokemon;
use App\Entity\Pokevolution;
use App\Entity\Talent;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokeRequest
{
    private $client;
    private EntityManagerInterface $em;

    public function __construct(
        HttpClientInterface $client,
        EntityManagerInterface $em
    ) {
        $this->client = $client;
        $this->em = $em;
    }

    //cette fonction ne doit etre utilisée qu'une fois pour peupler la BDD
    //la relancer pour vérifier des maj
    public function fromAPiToObjects(): array
    {
        $response = $this->client->request(
            'GET',
            'https://tyradex.vercel.app/api/v1/pokemon'
        );

        // Gestion d'erreur pour la requête HTTP
        if (200 !== $response->getStatusCode()) {
            throw new \Exception('Erreur lors de la récupération des données des Pokémon. Veuillez réessayer dans quelques instants...');
        }

        $content = $response->toArray();

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

        foreach ($content as $pokemon) {
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
                            $existingTalentNames[$talentData['name']] = $talent;
                            $this->em->persist($talent);
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
                            $existingTypeNames[$typeData['name']] = $type;
                            $this->em->persist($type);
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

            // Vérifier si une évolution existe déjà pour ce Pokémon
            $existingEvolution = $this->em->getRepository(Pokevolution::class)->findOneBy(['pokemon' => $poke]);

            if (!$existingEvolution) {
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
        }

        $this->em->flush();

        return $pokemonEntities;
    }
}

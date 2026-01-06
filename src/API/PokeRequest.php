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
    private const API_URL = 'https://tyradex.vercel.app/api/v1/pokemon';
    private const BATCH_SIZE = 50;

    /** @var array<string, Talent> */
    private array $talentCache = [];

    /** @var array<string, Type> */
    private array $typeCache = [];

    /** @var array<int, Pokemon> */
    private array $pokemonCache = [];

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EntityManagerInterface $em,
    ) {
    }

    /**
     * Peuple la BDD avec les données de l'API
     * @return Pokemon[]
     */
    public function fromAPiToObjects(): array
    {
        $content = $this->fetchPokemonData();

        $contentByPokedexId = $this->indexContentByPokedexId($content);
        
        $this->loadCaches();

        // Créer/récupérer les Pokémon
        $pokemonEntities = $this->processPokemonData($content);

        // Traiter les évolutions en batch
        $this->processEvolutions($pokemonEntities, $contentByPokedexId);

        // Nettoyer les caches
        $this->clearCaches();

        return $pokemonEntities;
    }

    /**
     * Récupère les données depuis l'API.
     */
    private function fetchPokemonData(): array
    {
        $response = $this->client->request('GET', self::API_URL, [
            'timeout' => 240,
        ]);

        if (200 !== $response->getStatusCode()) {
            throw new \RuntimeException(
                'Erreur lors de la récupération des données des Pokémon. Veuillez réessayer dans quelques instants...'
            );
        }

        return $response->toArray();
    }

    /**
     * Indexe le contenu de l'API par pokedex_id pour un accès rapide.
     *
     * @return array<int, array>
     */
    private function indexContentByPokedexId(array $content): array
    {
        $indexed = [];
        foreach ($content as $pokemon) {
            if (is_array($pokemon) && !empty($pokemon['pokedex_id'])) {
                $indexed[$pokemon['pokedex_id']] = $pokemon;
            }
        }

        return $indexed;
    }

    /**
     * Charge tous les caches nécessaires en une seule passe.
     */
    private function loadCaches(): void
    {
        // Cache des talents
        foreach ($this->em->getRepository(Talent::class)->findAll() as $talent) {
            $this->talentCache[$talent->getName()] = $talent;
        }

        // Cache des types
        foreach ($this->em->getRepository(Type::class)->findAll() as $type) {
            $this->typeCache[$type->getName()] = $type;
        }

        // Cache des Pokémon existants (indexé par pokedexId)
        foreach ($this->em->getRepository(Pokemon::class)->findAll() as $pokemon) {
            $this->pokemonCache[$pokemon->getPokedexId()] = $pokemon;
        }
    }

    /**
     * Traite les données Pokémon et retourne les entités.
     *
     * @return Pokemon[]
     */
    private function processPokemonData(array $content): array
    {
        $pokemonEntities = [];
        $batchCount = 0;

        foreach ($content as $pokemonData) {
            if (!is_array($pokemonData) || empty($pokemonData['pokedex_id'])) {
                continue;
            }

            // Vérifier si le Pokémon existe déjà en cache
            if (isset($this->pokemonCache[$pokemonData['pokedex_id']])) {
                $pokemonEntities[] = $this->pokemonCache[$pokemonData['pokedex_id']];
                continue;
            }

            $pokemon = $this->createPokemonFromData($pokemonData);
            $this->em->persist($pokemon);
            
            // Mettre à jour le cache
            $this->pokemonCache[$pokemon->getPokedexId()] = $pokemon;
            $pokemonEntities[] = $pokemon;

            // Flush par batch pour optimiser la mémoire
            if (++$batchCount % self::BATCH_SIZE === 0) {
                $this->em->flush();
            }
        }

        $this->em->flush();

        return $pokemonEntities;
    }

    /**
     * Crée une entité Pokemon à partir des données de l'API.
     */
    private function createPokemonFromData(array $data): Pokemon
    {
        $pokemon = new Pokemon();
        $pokemon->setPokedexId($data['pokedex_id']);
        $pokemon->setGeneration($data['generation'] ?? 0);
        $pokemon->setCategory($data['category'] ?? '');
        $pokemon->setName($data['name']['fr'] ?? 'Inconnu');

        // Stats (avec valeurs par défaut si null)
        $stats = $data['stats'] ?? [];
        $pokemon->setHp($stats['hp'] ?? 0);
        $pokemon->setAtk($stats['atk'] ?? 0);
        $pokemon->setDef($stats['def'] ?? 0);
        $pokemon->setSpeAtk($stats['spe_atk'] ?? 0);
        $pokemon->setSpeDef($stats['spe_def'] ?? 0);
        $pokemon->setVit($stats['vit'] ?? 0);

        // Dimensions
        $pokemon->setHeight($data['height'] ?? null);
        $pokemon->setWeight($data['weight'] ?? null);

        // Sprites
        if (!empty($data['sprites'])) {
            $this->setSprites($pokemon, $data['sprites']);
        }

        // Relations
        $this->attachTalents($pokemon, $data['talents'] ?? []);
        $this->attachTypes($pokemon, $data['types'] ?? []);

        return $pokemon;
    }

    /**
     * Configure les sprites du Pokémon.
     */
    private function setSprites(Pokemon $pokemon, array $sprites): void
    {
        $pokemon->setSpriteRegular($sprites['regular'] ?? null);
        
        if (!empty($sprites['shiny'])) {
            $pokemon->setSpriteShiny($sprites['shiny']);
        }
        
        if (!empty($sprites['gmax']['regular'])) {
            $pokemon->setSpriteGmax($sprites['gmax']['regular']);
        }
        
        if (!empty($sprites['gmax']['shiny'])) {
            $pokemon->setSpriteGmaxShiny($sprites['gmax']['shiny']);
        }
    }

    /**
     * Attache les talents au Pokémon (crée si nécessaire).
     */
    private function attachTalents(Pokemon $pokemon, array $talentsData): void
    {
        foreach ($talentsData as $talentData) {
            if (!is_array($talentData) || empty($talentData['name'])) {
                continue;
            }

            $name = $talentData['name'];
            
            if (!isset($this->talentCache[$name])) {
                $talent = new Talent();
                $talent->setName($name);
                $this->em->persist($talent);
                $this->talentCache[$name] = $talent;
            }

            $pokemon->addTalent($this->talentCache[$name]);
        }
    }

    /**
     * Attache les types au Pokémon (crée si nécessaire).
     */
    private function attachTypes(Pokemon $pokemon, array $typesData): void
    {
        foreach ($typesData as $typeData) {
            if (!is_array($typeData) || empty($typeData['name'])) {
                continue;
            }

            $name = $typeData['name'];
            
            if (!isset($this->typeCache[$name])) {
                $type = new Type();
                $type->setName($name);
                $type->setImage($typeData['image'] ?? null);
                $this->em->persist($type);
                $this->typeCache[$name] = $type;
            }

            $pokemon->addType($this->typeCache[$name]);
        }
    }

    /**
     * Traite les évolutions pour tous les Pokémon.
     *
     * @param Pokemon[] $pokemonEntities
     * @param array<int, array> $contentByPokedexId
     */
    private function processEvolutions(array $pokemonEntities, array $contentByPokedexId): void
    {
        // Charger toutes les évolutions existantes en une seule requête
        $existingEvolutions = $this->em->getRepository(Pokevolution::class)->findAll();
        $evolutionsByPokemonId = [];
        foreach ($existingEvolutions as $evolution) {
            $evolutionsByPokemonId[$evolution->getPokemon()->getId()] = $evolution;
        }

        $batchCount = 0;

        foreach ($pokemonEntities as $pokemon) {
            // Vérifier si l'évolution existe déjà
            if (isset($evolutionsByPokemonId[$pokemon->getId()])) {
                continue;
            }

            $pokemonData = $contentByPokedexId[$pokemon->getPokedexId()] ?? null;
            if (null === $pokemonData) {
                continue;
            }

            $evolution = $this->createEvolution($pokemon, $pokemonData['evolution'] ?? []);
            $this->em->persist($evolution);

            if (++$batchCount % self::BATCH_SIZE === 0) {
                $this->em->flush();
            }
        }

        $this->em->flush();
    }

    /**
     * Crée une entité Pokevolution à partir des données.
     */
    private function createEvolution(Pokemon $pokemon, array $evolutionData): Pokevolution
    {
        $evolution = new Pokevolution();
        $evolution->setPokemon($pokemon);

        // Pré-évolutions
        if (!empty($evolutionData['pre'])) {
            $this->setEvolutionRelations(
                $evolution,
                $evolutionData['pre'],
                'setPreEvolution1',
                'setPreEvolution2'
            );
        }

        // Évolutions suivantes
        if (!empty($evolutionData['next'])) {
            $this->setEvolutionRelations(
                $evolution,
                $evolutionData['next'],
                'setNextEvolution1',
                'setNextEvolution2'
            );
        }

        // Méga-évolution
        if (!empty($evolutionData['mega'])) {
            $evolution->setMegaEvolution(true);
        }

        return $evolution;
    }

    /**
     * Configure les relations d'évolution (pré ou next).
     */
    private function setEvolutionRelations(
        Pokevolution $evolution,
        array $relations,
        string $setter1,
        string $setter2
    ): void {
        if (isset($relations[0]) && is_array($relations[0]) && !empty($relations[0]['pokedex_id'])) {
            $pokemon1 = $this->pokemonCache[$relations[0]['pokedex_id']] ?? null;
            if ($pokemon1) {
                $evolution->$setter1($pokemon1);
            }
        }

        if (isset($relations[1]) && is_array($relations[1]) && !empty($relations[1]['pokedex_id'])) {
            $pokemon2 = $this->pokemonCache[$relations[1]['pokedex_id']] ?? null;
            if ($pokemon2) {
                $evolution->$setter2($pokemon2);
            }
        }
    }

    /**
     * Libère la mémoire des caches.
     */
    private function clearCaches(): void
    {
        $this->talentCache = [];
        $this->typeCache = [];
        $this->pokemonCache = [];
    }
}

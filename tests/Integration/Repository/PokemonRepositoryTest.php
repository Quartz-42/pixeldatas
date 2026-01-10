<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Pokemon;
use App\Entity\Type;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PokemonRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private PokemonRepository $pokemonRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var PokemonRepository $repository */
        $repository = $this->entityManager->getRepository(Pokemon::class);
        $this->pokemonRepository = $repository;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
    }

    public function testFindBySearchQueryBuilderWithQuery(): void
    {
        $queryBuilder = $this->pokemonRepository->findBySearchQueryBuilder('pika');
        $result = $queryBuilder->getQuery()->getResult();

        $this->assertIsArray($result);
        
        // Vérifie que tous les résultats contiennent 'pika' dans le nom
        foreach ($result as $pokemon) {
            $this->assertInstanceOf(Pokemon::class, $pokemon);
            $this->assertStringContainsStringIgnoringCase('pika', $pokemon->getName());
        }
    }

    public function testFindBySearchQueryBuilderWithEmptyQuery(): void
    {
        $queryBuilder = $this->pokemonRepository->findBySearchQueryBuilder('');
        $result = $queryBuilder->getQuery()->getResult();

        $this->assertIsArray($result);
    }

    public function testGetPokemonsByGenerationForSearch(): void
    {
        $generation = 1;
        $queryBuilder = $this->pokemonRepository->getPokemonsByGenerationForSearch($generation, null);
        $result = $queryBuilder->getQuery()->getResult();

        $this->assertIsArray($result);
        
        foreach ($result as $pokemon) {
            $this->assertInstanceOf(Pokemon::class, $pokemon);
            $this->assertEquals($generation, $pokemon->getGeneration());
        }
    }

    public function testGetPokemonsByGenerationForSearchWithQuery(): void
    {
        $generation = 1;
        $query = 'pika';
        
        $queryBuilder = $this->pokemonRepository->getPokemonsByGenerationForSearch($generation, $query);
        $result = $queryBuilder->getQuery()->getResult();

        $this->assertIsArray($result);
        
        foreach ($result as $pokemon) {
            $this->assertInstanceOf(Pokemon::class, $pokemon);
            $this->assertEquals($generation, $pokemon->getGeneration());
            $this->assertStringContainsStringIgnoringCase($query, $pokemon->getName());
        }
    }

    public function testGetPokemonsByTypeForSearch(): void
    {
        $type = 'Électrik';
        
        $queryBuilder = $this->pokemonRepository->getPokemonsByTypeForSearch($type, null);
        $result = $queryBuilder->getQuery()->getResult();

        $this->assertIsArray($result);
        
        foreach ($result as $pokemon) {
            $this->assertInstanceOf(Pokemon::class, $pokemon);
            
            // Vérifie que le Pokemon a bien le type demandé
            $hasType = false;
            foreach ($pokemon->getTypes() as $pokemonType) {
                if ($pokemonType->getName() === $type) {
                    $hasType = true;
                    break;
                }
            }
            $this->assertTrue($hasType, "Le Pokemon {$pokemon->getName()} devrait avoir le type {$type}");
        }
    }

    public function testGetPokemonsByTypeForSearchWithQuery(): void
    {
        $type = 'Électrik';
        $query = 'chu';
        
        $queryBuilder = $this->pokemonRepository->getPokemonsByTypeForSearch($type, $query);
        $result = $queryBuilder->getQuery()->getResult();

        $this->assertIsArray($result);
        
        foreach ($result as $pokemon) {
            $this->assertInstanceOf(Pokemon::class, $pokemon);
            $this->assertStringContainsStringIgnoringCase($query, $pokemon->getName());
        }
    }

    public function testGetRandomPokemons(): void
    {
        $count = 5;
        $pokemons = $this->pokemonRepository->getRandomPokemons($count);

        $this->assertLessThanOrEqual($count, count($pokemons));
        
        foreach ($pokemons as $pokemon) {
            $this->assertInstanceOf(Pokemon::class, $pokemon);
        }
    }

    public function testGetRandomPokemonsReturnsRequestedCount(): void
    {
        $count = 3;
        $pokemons = $this->pokemonRepository->getRandomPokemons($count);

        // Vérifie qu'on a bien le nombre demandé ou moins s'il n'y a pas assez de données
        $this->assertLessThanOrEqual($count, count($pokemons));
    }

    public function testRepositoryExists(): void
    {
        $this->assertInstanceOf(PokemonRepository::class, $this->pokemonRepository);
    }
}

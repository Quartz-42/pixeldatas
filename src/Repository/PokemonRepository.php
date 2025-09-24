<?php

namespace App\Repository;

use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pokemon>
 */
class PokemonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
    }

    public function getRandomPokemons(int $count): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM pokemon
            ORDER BY RAND() 
            LIMIT :count';

        $stmt = $conn->prepare($sql);
        $stmt->bindValue('count', $count, \PDO::PARAM_INT);
        $resultSet = $stmt->executeQuery();

        $result = $resultSet->fetchAllAssociative();

        // Transformer chaque ligne en objet Pokemon
        $pokemonRepository = $this->getEntityManager()->getRepository(Pokemon::class);
        $pokemons = [];

        foreach ($result as $row) {
            $pokemon = $pokemonRepository->find($row['id']);
            if ($pokemon) {
                $pokemons[] = $pokemon;
            }
        }

        return $pokemons;
    }

    public function findBySearchQueryBuilder(?string $query): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
        ->leftJoin('p.types', 't')
        ->addSelect('t');
        
        $qb->andWhere('p.name LIKE :query')
            ->setParameter('query', '%'.$query.'%');

        return $qb;
    }

    public function getPokemonsByGenerationForSearch($generation, ?string $query)
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.types', 't')
            ->addSelect('t')
            ->where('p.generation = :generation')
            ->setParameter(':generation', $generation);

        if ($query) {
            $qb->andWhere('p.name LIKE :query')
                ->setParameter('query', '%'.$query.'%');
        }

        return $qb;
    }

    public function getPokemonsByTypeForSearch(string $type, ?string $query)
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.types', 't')
            ->addSelect('t')
            ->where('t.name = :type')
            ->setParameter('type', $type);

        if ($query) {
            $qb->andWhere('p.name LIKE :query')
                ->setParameter('query', '%'.$query.'%');
        }

        return $qb;
    }

    public function getPokemonsByGeneration(int $generation)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.types', 't')
            ->addSelect('t')
            ->where('p.generation = :generation')
            ->setParameter(':generation', $generation)
            ->orderBy('p.generation', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getPokemonsByType(string $type)
    {
        return $this->createQueryBuilder('p')
            ->join('p.types', 't') 
            ->addSelect('t')
            ->where('t.name = :type') 
            ->setParameter(':type', $type)
            ->getQuery()
            ->getResult();
    }

    public function findPokemonTypes(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.types', 't')
            ->select('DISTINCT t.image, t.name')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findPokemonGenerations()
    {
        $results = $this->createQueryBuilder('p')
            ->select('DISTINCT p.generation')
            ->orderBy('p.generation', 'ASC')
            ->getQuery()
            ->getResult();

        return array_column($results, 'generation');
    }
}

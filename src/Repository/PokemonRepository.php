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
        // 1. Récupérer tous les IDs
        $ids = $this->createQueryBuilder('p')
            ->select('p.id')
            ->getQuery()
            ->getSingleColumnResult();

        // 2. Mélanger et prendre X IDs au hasard
        shuffle($ids);
        $randomIds = array_slice($ids, 0, $count);

        return $this->createQueryBuilder('p')
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $randomIds)
            ->getQuery()
            ->getResult();
    }

    private function createSearchQueryBuilder(?string $search = null, ?string $type = null, ?int $generation = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.types', 't')
            ->addSelect('t')
            ->orderBy('p.pokedexId', 'ASC');

        if ($search) {
            $qb->andWhere('p.name LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        if ($generation) {
            $qb->andWhere('p.generation = :generation')
                ->setParameter('generation', $generation);
        }

        if ($type) {
            $qb->andWhere('p.id IN (
                SELECT p2.id 
                FROM App\Entity\Pokemon p2 
                JOIN p2.types t2 
                WHERE t2.name = :type
            )')
            ->setParameter('type', $type);
        }

        return $qb;
    }

    public function findBySearchQueryBuilder(?string $query): QueryBuilder
    {
        return $this->createSearchQueryBuilder($query);
    }

    public function getPokemonsByGenerationForSearch($generation, ?string $query): QueryBuilder
    {
        return $this->createSearchQueryBuilder($query, null, (int) $generation);
    }

    public function getPokemonsByTypeForSearch(string $type, ?string $query): QueryBuilder
    {
        return $this->createSearchQueryBuilder($query, $type);
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

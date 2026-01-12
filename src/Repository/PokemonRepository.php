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

    /**
     * @return Pokemon[]
     */
    public function getRandomPokemons(int $count): array
    {
        $ids = $this->createQueryBuilder('p')
            ->select('p.id')
            ->getQuery()
            ->getSingleColumnResult();

        shuffle($ids);
        $randomIds = array_slice($ids, 0, $count);

        return $this->createQueryBuilder('p')
            ->leftJoin('p.types', 't')
            ->addSelect('t')
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $randomIds)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int[] $ids
     *
     * @return Pokemon[]
     */
    public function findByIdsWithRelations(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        return $this->createQueryBuilder('p')
            ->leftJoin('p.types', 't')
            ->addSelect('t')
            ->leftJoin('p.pokevolutions', 'pe')
            ->addSelect('pe')
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $ids)
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

    public function getPokemonsByGenerationForSearch(int $generation, ?string $query): QueryBuilder
    {
        return $this->createSearchQueryBuilder($query, null, $generation);
    }

    public function getPokemonsByTypeForSearch(string $type, ?string $query): QueryBuilder
    {
        return $this->createSearchQueryBuilder($query, $type);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findPokemonTypes(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.types', 't')
            ->select('DISTINCT t.image, t.name')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int[]
     */
    public function findPokemonGenerations(): array
    {
        $results = $this->createQueryBuilder('p')
            ->select('DISTINCT p.generation')
            ->orderBy('p.generation', 'ASC')
            ->getQuery()
            ->getResult();

        return array_column($results, 'generation');
    }

    /**
     * Trouve un Pokémon par son nom avec toutes ses relations chargées.
     */
    public function findOneByNameWithRelations(string $name): ?Pokemon
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.types', 't')
            ->addSelect('t')
            ->leftJoin('p.talent', 'talent')
            ->addSelect('talent')
            ->leftJoin('p.resistances', 'r')
            ->addSelect('r')
            ->leftJoin('r.type', 'rt')
            ->addSelect('rt')
            ->leftJoin('p.pokevolutions', 'evo')
            ->addSelect('evo')
            ->leftJoin('evo.preEvolution1', 'pre1')
            ->addSelect('pre1')
            ->leftJoin('evo.preEvolution2', 'pre2')
            ->addSelect('pre2')
            ->leftJoin('evo.nextEvolution1', 'next1')
            ->addSelect('next1')
            ->leftJoin('evo.nextEvolution2', 'next2')
            ->addSelect('next2')
            ->where('p.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

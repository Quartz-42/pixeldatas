<?php

namespace App\Repository;

use App\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Type>
 */
class TypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Type::class);
    }

    public function getPokemonTypes(int $pokemonId)
    {
        $this->createQueryBuilder('t')
            ->select('t.name')
            ->innerJoin('t.pokemons', 'p')
            ->where('p.id = :pokemonId')
            ->setParameter('pokemonId', $pokemonId)
            ->getQuery()
            ->getResult();
    }
}

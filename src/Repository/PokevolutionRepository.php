<?php

namespace App\Repository;

use App\Entity\Pokevolution;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pokevolution>
 */
class PokevolutionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokevolution::class);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findAllEvoliNames(): array
    {
        return $this->createQueryBuilder('e')
            ->select('p.name')
            ->join('e.pokemon', 'p')
            ->where('e.preEvolution1 = :evoliId')
            ->setParameter('evoliId', 133)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

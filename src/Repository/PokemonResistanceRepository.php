<?php

namespace App\Repository;

use App\Entity\PokemonResistance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PokemonResistance>
 *
 * @method PokemonResistance|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonResistance|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonResistance[]    findAll()
 * @method PokemonResistance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonResistanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonResistance::class);
    }
}

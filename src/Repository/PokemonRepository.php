<?php

namespace App\Repository;

use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

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
        $qb = $this->createQueryBuilder('p');

        if ($query) {
            $qb->andWhere('p.name LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        return $qb;
    }
}

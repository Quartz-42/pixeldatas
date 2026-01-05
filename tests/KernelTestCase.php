<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as BaseKernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Base class pour les tests d'intégration
 * Fournit un accès simplifié à l'EntityManager
 */
abstract class KernelTestCase extends BaseKernelTestCase
{
    protected ?EntityManagerInterface $entityManager = null;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->entityManager !== null) {
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }

    /**
     * Persiste et flush une entité
     */
    protected function persistAndFlush(object $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * Supprime et flush une entité
     */
    protected function removeAndFlush(object $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    /**
     * Vide l'EntityManager
     */
    protected function clearEntityManager(): void
    {
        $this->entityManager->clear();
    }
}

<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Type;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TypeRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?TypeRepository $typeRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->typeRepository = $this->entityManager->getRepository(Type::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testCanFindAllTypes(): void
    {
        $types = $this->typeRepository->findAll();

        $this->assertIsArray($types);
        
        foreach ($types as $type) {
            $this->assertInstanceOf(Type::class, $type);
            $this->assertNotEmpty($type->getName());
        }
    }

    public function testCanFindTypeByName(): void
    {
        // Assume qu'au moins un type existe
        $allTypes = $this->typeRepository->findAll();
        
        if (count($allTypes) > 0) {
            $typeName = $allTypes[0]->getName();
            $type = $this->typeRepository->findOneBy(['name' => $typeName]);

            $this->assertInstanceOf(Type::class, $type);
            $this->assertEquals($typeName, $type->getName());
        } else {
            $this->markTestSkipped('Aucun type trouvé dans la base de données.');
        }
    }

    public function testRepositoryExists(): void
    {
        $this->assertNotNull($this->typeRepository);
        $this->assertInstanceOf(TypeRepository::class, $this->typeRepository);
    }
}

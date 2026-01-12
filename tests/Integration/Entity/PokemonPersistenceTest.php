<?php

namespace App\Tests\Integration\Entity;

use App\Entity\Pokemon;
use App\Entity\Type;
use App\Entity\Talent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PokemonPersistenceTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

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

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testCanPersistAndRetrievePokemon(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setPokedexId(9999)
            ->setGeneration(1)
            ->setCategory('Test')
            ->setName('TestPokemon' . uniqid())
            ->setSpriteRegular('test.png')
            ->setHp(100)
            ->setAtk(100)
            ->setDef(100)
            ->setSpeAtk(100)
            ->setSpeDef(100)
            ->setVit(100)
            ->setHeight('1.0m')
            ->setWeight('10.0kg');

        $this->entityManager->persist($pokemon);
        $this->entityManager->flush();

        $this->assertNotNull($pokemon->getId());

        // Récupérer le Pokemon
        $foundPokemon = $this->entityManager
            ->getRepository(Pokemon::class)
            ->find($pokemon->getId());

        $this->assertNotNull($foundPokemon);
        $this->assertEquals('TestPokemon' . substr($pokemon->getName(), -13), $foundPokemon->getName());
        
        // Suppression du pokemon en base
        $this->entityManager->remove($foundPokemon);
        $this->entityManager->flush();
    }

    public function testPokemonTypeRelationship(): void
    {
        $type = new Type();
        $type->setName('TestType' . uniqid())
            ->setImage('test.png');

        $pokemon = new Pokemon();
        $pokemon->setPokedexId(9998)
            ->setGeneration(1)
            ->setCategory('Test')
            ->setName('TestPokemon' . uniqid())
            ->setSpriteRegular('test.png')
            ->setHp(100)
            ->setAtk(100)
            ->setDef(100)
            ->setSpeAtk(100)
            ->setSpeDef(100)
            ->setVit(100)
            ->setHeight('1.0m')
            ->setWeight('10.0kg');

        $pokemon->addType($type);

        $this->entityManager->persist($type);
        $this->entityManager->persist($pokemon);
        $this->entityManager->flush();

        $this->assertCount(1, $pokemon->getTypes());
        $this->assertTrue($pokemon->getTypes()->contains($type));

        // Suppression du pokemon en base
        $this->entityManager->remove($pokemon);
        $this->entityManager->remove($type);
        $this->entityManager->flush();
    }

    public function testPokemonTalentRelationship(): void
    {
        $talent = new Talent();
        $talent->setName('TestTalent' . uniqid());

        $pokemon = new Pokemon();
        $pokemon->setPokedexId(9997)
            ->setGeneration(1)
            ->setCategory('Test')
            ->setName('TestPokemon' . uniqid())
            ->setSpriteRegular('test.png')
            ->setHp(100)
            ->setAtk(100)
            ->setDef(100)
            ->setSpeAtk(100)
            ->setSpeDef(100)
            ->setVit(100)
            ->setHeight('1.0m')
            ->setWeight('10.0kg');

        $pokemon->addTalent($talent);

        $this->entityManager->persist($talent);
        $this->entityManager->persist($pokemon);
        $this->entityManager->flush();

        $this->assertCount(1, $pokemon->getTalent());
        $this->assertTrue($pokemon->getTalent()->contains($talent));

        // Suppression du pokemon en base
        $this->entityManager->remove($pokemon);
        $this->entityManager->remove($talent);
        $this->entityManager->flush();
    }
}

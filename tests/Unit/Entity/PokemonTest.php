<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Pokemon;
use App\Entity\Talent;
use App\Entity\Type;
use App\Entity\Pokevolution;
use PHPUnit\Framework\TestCase;

class PokemonTest extends TestCase
{
    private Pokemon $pokemon;

    protected function setUp(): void
    {
        $this->pokemon = new Pokemon();
    }

    public function testCanSetAndGetPokedexId(): void
    {
        $this->pokemon->setPokedexId(25);
        $this->assertSame(25, $this->pokemon->getPokedexId());
    }

    public function testCanSetAndGetGeneration(): void
    {
        $this->pokemon->setGeneration(1);
        $this->assertSame(1, $this->pokemon->getGeneration());
    }

    public function testCanSetAndGetCategory(): void
    {
        $this->pokemon->setCategory('Souris');
        $this->assertSame('Souris', $this->pokemon->getCategory());
    }

    public function testCanSetAndGetName(): void
    {
        $this->pokemon->setName('Pikachu');
        $this->assertSame('Pikachu', $this->pokemon->getName());
    }

    public function testCanSetAndGetSprites(): void
    {
        $this->pokemon->setSpriteRegular('pikachu.png');
        $this->assertSame('pikachu.png', $this->pokemon->getSpriteRegular());

        $this->pokemon->setSpriteShiny('pikachu-shiny.png');
        $this->assertSame('pikachu-shiny.png', $this->pokemon->getSpriteShiny());

        $this->pokemon->setSpriteGmax('pikachu-gmax.png');
        $this->assertSame('pikachu-gmax.png', $this->pokemon->getSpriteGmax());

        $this->pokemon->setSpriteGmaxShiny('pikachu-gmax-shiny.png');
        $this->assertSame('pikachu-gmax-shiny.png', $this->pokemon->getSpriteGmaxShiny());
    }

    public function testCanSetAndGetStats(): void
    {
        $this->pokemon->setHp(35);
        $this->assertSame(35, $this->pokemon->getHp());

        $this->pokemon->setAtk(55);
        $this->assertSame(55, $this->pokemon->getAtk());

        $this->pokemon->setDef(40);
        $this->assertSame(40, $this->pokemon->getDef());

        $this->pokemon->setSpeAtk(50);
        $this->assertSame(50, $this->pokemon->getSpeAtk());

        $this->pokemon->setSpeDef(50);
        $this->assertSame(50, $this->pokemon->getSpeDef());

        $this->pokemon->setVit(90);
        $this->assertSame(90, $this->pokemon->getVit());
    }

    public function testCanSetAndGetHeight(): void
    {
        $this->pokemon->setHeight('0.4m');
        $this->assertSame('0.4m', $this->pokemon->getHeight());
    }

    public function testCanSetAndGetWeight(): void
    {
        $this->pokemon->setWeight('6.0kg');
        $this->assertSame('6.0kg', $this->pokemon->getWeight());
    }

    public function testCanAddAndRemoveTalents(): void
    {
        $talent = new Talent();
        $talent->setName('Statik');

        $this->pokemon->addTalent($talent);
        $this->assertCount(1, $this->pokemon->getTalent());
        $this->assertTrue($this->pokemon->getTalent()->contains($talent));

        $this->pokemon->removeTalent($talent);
        $this->assertCount(0, $this->pokemon->getTalent());
        $this->assertFalse($this->pokemon->getTalent()->contains($talent));
    }

    public function testCanAddAndRemoveTypes(): void
    {
        $type = new Type();
        $type->setName('Électrik');

        $this->pokemon->addType($type);
        $this->assertCount(1, $this->pokemon->getTypes());
        $this->assertTrue($this->pokemon->getTypes()->contains($type));

        $this->pokemon->removeType($type);
        $this->assertCount(0, $this->pokemon->getTypes());
        $this->assertFalse($this->pokemon->getTypes()->contains($type));
    }

    public function testCanAddMultipleTypes(): void
    {
        $type1 = new Type();
        $type1->setName('Électrik');

        $type2 = new Type();
        $type2->setName('Acier');

        $this->pokemon->addType($type1);
        $this->pokemon->addType($type2);

        $this->assertCount(2, $this->pokemon->getTypes());
    }

    public function testDoesNotAddDuplicateTalents(): void
    {
        $talent = new Talent();
        $talent->setName('Statik');

        $this->pokemon->addTalent($talent);
        $this->pokemon->addTalent($talent);

        $this->assertCount(1, $this->pokemon->getTalent());
    }

    public function testDoesNotAddDuplicateTypes(): void
    {
        $type = new Type();
        $type->setName('Électrik');

        $this->pokemon->addType($type);
        $this->pokemon->addType($type);

        $this->assertCount(1, $this->pokemon->getTypes());
    }

    public function testInitiallyHasEmptyCollections(): void
    {
        $pokemon = new Pokemon();

        $this->assertCount(0, $pokemon->getTalent());
        $this->assertCount(0, $pokemon->getTypes());
        $this->assertCount(0, $pokemon->getPokevolutions());
        $this->assertCount(0, $pokemon->getPreEvolution1());
        $this->assertCount(0, $pokemon->getPreEvolution2());
        $this->assertCount(0, $pokemon->getNextEvolution1());
        $this->assertCount(0, $pokemon->getNextEvolution2());
    }
}

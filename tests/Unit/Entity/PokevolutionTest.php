<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Pokevolution;
use App\Entity\Pokemon;
use PHPUnit\Framework\TestCase;

class PokevolutionTest extends TestCase
{
    private Pokevolution $pokevolution;

    protected function setUp(): void
    {
        $this->pokevolution = new Pokevolution();
    }

    public function testCanSetAndGetPokemon(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setName('Pikachu');

        $this->pokevolution->setPokemon($pokemon);
        $this->assertSame($pokemon, $this->pokevolution->getPokemon());
    }

    public function testCanSetAndGetPreEvolution1(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setName('Pichu');

        $this->pokevolution->setPreEvolution1($pokemon);
        $this->assertSame($pokemon, $this->pokevolution->getPreEvolution1());
    }

    public function testCanSetAndGetPreEvolution2(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setName('Pichu');

        $this->pokevolution->setPreEvolution2($pokemon);
        $this->assertSame($pokemon, $this->pokevolution->getPreEvolution2());
    }

    public function testCanSetAndGetNextEvolution1(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setName('Raichu');

        $this->pokevolution->setNextEvolution1($pokemon);
        $this->assertSame($pokemon, $this->pokevolution->getNextEvolution1());
    }

    public function testCanSetAndGetNextEvolution2(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setName('Raichu d\'Alola');

        $this->pokevolution->setNextEvolution2($pokemon);
        $this->assertSame($pokemon, $this->pokevolution->getNextEvolution2());
    }

    public function testCanSetAndCheckIsMegaEvolution(): void
    {
        $this->pokevolution->setMegaEvolution(true);
        $this->assertTrue($this->pokevolution->isMegaEvolution());

        $this->pokevolution->setMegaEvolution(false);
        $this->assertFalse($this->pokevolution->isMegaEvolution());
    }

    public function testIsMegaEvolutionInitiallyNull(): void
    {
        $this->assertNull($this->pokevolution->isMegaEvolution());
    }

    public function testGetIdReturnsNullForNewEntity(): void
    {
        $this->assertNull($this->pokevolution->getId());
    }

    public function testCanSetMultipleEvolutionRelationships(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setName('Pikachu');

        $preEvolution = new Pokemon();
        $preEvolution->setName('Pichu');

        $nextEvolution = new Pokemon();
        $nextEvolution->setName('Raichu');

        $this->pokevolution->setPokemon($pokemon);
        $this->pokevolution->setPreEvolution1($preEvolution);
        $this->pokevolution->setNextEvolution1($nextEvolution);

        $this->assertSame($pokemon, $this->pokevolution->getPokemon());
        $this->assertSame($preEvolution, $this->pokevolution->getPreEvolution1());
        $this->assertSame($nextEvolution, $this->pokevolution->getNextEvolution1());
    }
}

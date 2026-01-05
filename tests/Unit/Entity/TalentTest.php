<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Talent;
use App\Entity\Pokemon;
use PHPUnit\Framework\TestCase;

class TalentTest extends TestCase
{
    private Talent $talent;

    protected function setUp(): void
    {
        $this->talent = new Talent();
    }

    public function testCanSetAndGetName(): void
    {
        $this->talent->setName('Statik');
        $this->assertSame('Statik', $this->talent->getName());
    }

    public function testCanAddPokemon(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setName('Pikachu');

        $this->talent->addPokemon($pokemon);
        
        $this->assertCount(1, $this->talent->getPokemons());
        $this->assertTrue($this->talent->getPokemons()->contains($pokemon));
    }

    public function testCanRemovePokemon(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setName('Pikachu');

        $this->talent->addPokemon($pokemon);
        $this->assertCount(1, $this->talent->getPokemons());

        $this->talent->removePokemon($pokemon);
        $this->assertCount(0, $this->talent->getPokemons());
        $this->assertFalse($this->talent->getPokemons()->contains($pokemon));
    }

    public function testDoesNotAddDuplicatePokemon(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setName('Pikachu');

        $this->talent->addPokemon($pokemon);
        $this->talent->addPokemon($pokemon);

        $this->assertCount(1, $this->talent->getPokemons());
    }

    public function testCanAddMultiplePokemons(): void
    {
        $pokemon1 = new Pokemon();
        $pokemon1->setName('Pikachu');

        $pokemon2 = new Pokemon();
        $pokemon2->setName('Raichu');

        $this->talent->addPokemon($pokemon1);
        $this->talent->addPokemon($pokemon2);

        $this->assertCount(2, $this->talent->getPokemons());
    }

    public function testInitiallyHasEmptyPokemonCollection(): void
    {
        $talent = new Talent();
        $this->assertCount(0, $talent->getPokemons());
    }

    public function testGetIdReturnsNullForNewEntity(): void
    {
        $this->assertNull($this->talent->getId());
    }
}

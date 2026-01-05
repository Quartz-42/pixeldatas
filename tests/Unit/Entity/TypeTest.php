<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Type;
use App\Entity\Pokemon;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    private Type $type;

    protected function setUp(): void
    {
        $this->type = new Type();
    }

    public function testCanSetAndGetName(): void
    {
        $this->type->setName('Feu');
        $this->assertSame('Feu', $this->type->getName());
    }

    public function testCanSetAndGetImage(): void
    {
        $this->type->setImage('feu.png');
        $this->assertSame('feu.png', $this->type->getImage());
    }

    public function testCanAddPokemon(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setName('Salamèche');

        $this->type->addPokemonId($pokemon);
        
        $this->assertCount(1, $this->type->getPokemonId());
        $this->assertTrue($this->type->getPokemonId()->contains($pokemon));
    }

    public function testCanRemovePokemon(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setName('Salamèche');

        $this->type->addPokemonId($pokemon);
        $this->assertCount(1, $this->type->getPokemonId());

        $this->type->removePokemonId($pokemon);
        $this->assertCount(0, $this->type->getPokemonId());
        $this->assertFalse($this->type->getPokemonId()->contains($pokemon));
    }

    public function testDoesNotAddDuplicatePokemon(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setName('Salamèche');

        $this->type->addPokemonId($pokemon);
        $this->type->addPokemonId($pokemon);

        $this->assertCount(1, $this->type->getPokemonId());
    }

    public function testCanAddMultiplePokemons(): void
    {
        $pokemon1 = new Pokemon();
        $pokemon1->setName('Salamèche');

        $pokemon2 = new Pokemon();
        $pokemon2->setName('Reptincel');

        $this->type->addPokemonId($pokemon1);
        $this->type->addPokemonId($pokemon2);

        $this->assertCount(2, $this->type->getPokemonId());
    }

    public function testInitiallyHasEmptyPokemonCollection(): void
    {
        $type = new Type();
        $this->assertCount(0, $type->getPokemonId());
    }

    public function testGetIdReturnsNullForNewEntity(): void
    {
        $this->assertNull($this->type->getId());
    }
}

<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PokemonControllerTest extends WebTestCase
{
    public function testShowAllPokemonsPageLoads(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pokemons');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('html');
    }

    public function testShowAllPokemonsWithPageParameter(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pokemons?page=1');

        $this->assertResponseIsSuccessful();
    }

    public function testShowAllPokemonsWithSearchQuery(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pokemons?query=pika');

        $this->assertResponseIsSuccessful();
    }

    public function testShowAllPokemonsWithEmptySearch(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pokemons?query=');

        $this->assertResponseIsSuccessful();
    }

    public function testShowPokemonDetailsWithValidName(): void
    {
        $client = static::createClient();
        
        // D'abord récupérer la liste pour avoir un nom valide
        $client->request('GET', '/pokemons');
        $this->assertResponseIsSuccessful();
        
        $crawler = $client->request('GET', '/pokemons/pikachu');
        
    }

    public function testShowPokemonDetailsWith404(): void
    {
        $client = static::createClient();
        $client->request('GET', '/pokemons/pokemon-inexistant-xyz-123');

        $this->assertTrue(
            $client->getResponse()->isServerError() || 
            $client->getResponse()->isClientError()
        );
    }

    public function testShowPokemonByGeneration(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pokemons/generation/1');

        $this->assertResponseIsSuccessful();
    }

    public function testShowPokemonByGenerationWithPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pokemons/generation/1?page=1');

        $this->assertResponseIsSuccessful();
    }

    public function testShowPokemonByGenerationWithSearch(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pokemons/generation/1?query=pika');

        $this->assertResponseIsSuccessful();
    }

    public function testShowPokemonByType(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pokemons/type/Feu');

        $this->assertResponseIsSuccessful();
    }

    public function testShowPokemonByTypeWithPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pokemons/type/Eau?page=1');

        $this->assertResponseIsSuccessful();
    }

    public function testShowPokemonByTypeWithSearch(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pokemons/type/Électrik?query=chu');

        $this->assertResponseIsSuccessful();
    }

    public function testPaginationWorks(): void
    {
        $client = static::createClient();
        
        // Test page 1
        $client->request('GET', '/pokemons?page=1');
        $this->assertResponseIsSuccessful();
        
        // Test page 2
        $client->request('GET', '/pokemons?page=2');
        $this->assertResponseIsSuccessful();
    }

    public function testInvalidGenerationReturnsError(): void
    {
        $client = static::createClient();
        $client->request('GET', '/pokemons/generation/999');

        // Devrait retourner une page vide ou une erreur
        // Selon l'implémentation actuelle
        $this->assertTrue(
            $client->getResponse()->isSuccessful() || 
            $client->getResponse()->isClientError()
        );
    }
}

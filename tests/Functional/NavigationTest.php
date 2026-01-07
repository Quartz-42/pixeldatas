<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NavigationTest extends WebTestCase
{
    public function testNavigationBetweenPages(): void
    {
        $client = static::createClient();
        
        // Commencer à la page d'accueil
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        
        // Naviguer vers la liste des Pokémon
        $client->request('GET', '/pokemons');
        $this->assertResponseIsSuccessful();
    }

    public function testAllMainRoutesAreAccessible(): void
    {
        $client = static::createClient();
        
        $routes = [
            '/',
            '/pokemons',
            '/pokemons/generation/1',
        ];
        
        foreach ($routes as $route) {
            $client->request('GET', $route);
            $this->assertResponseIsSuccessful("La route {$route} devrait être accessible");
        }
    }

    public function testPaginationLinksWork(): void
    {
        $client = static::createClient();
        
        // Tester différentes pages
        for ($page = 1; $page <= 3; $page++) {
            $client->request('GET', "/pokemons?page={$page}");
            $this->assertResponseIsSuccessful("La page {$page} devrait être accessible");
        }
    }

    public function testSearchFunctionalityAcrossPages(): void
    {
        $client = static::createClient();
        
        $searchQueries = ['pika', 'char', 'bulb', ''];
        
        foreach ($searchQueries as $query) {
            $client->request('GET', '/pokemons', ['query' => $query]);
            $this->assertResponseIsSuccessful("La recherche pour '{$query}' devrait fonctionner");
        }
    }

    public function testGenerationFiltersWork(): void
    {
        $client = static::createClient();
        
        // Tester les différentes générations
        for ($gen = 1; $gen <= 9; $gen++) {
            $client->request('GET', "/pokemons/generation/{$gen}");
            $this->assertTrue(
                $client->getResponse()->isSuccessful() || 
                $client->getResponse()->isNotFound(),
                "La génération {$gen} devrait être gérée correctement"
            );
        }
    }
}

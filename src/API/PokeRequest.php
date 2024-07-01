<?php

namespace App\API;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PokeRequest
{
    private $client;
    private $cache;

    public function __construct(HttpClientInterface $client, CacheInterface $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    public function getRandomPokemon(int $count): array
    {
        return $this->cache->get('randomPoke', function (ItemInterface $item) use ($count): array {

            $item->expiresAfter(5);

            $response = $this->client->request('GET', 'https://tyradex.vercel.app/api/v1/pokemon');

            // Gestion d'erreur pour la requête HTTP
            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Erreur lors de la récupération des données des Pokémon');
            }

            $content = $response->toArray();

            shuffle($content);

            return array_slice($content, 0, $count);
        });
    }

    public function getAllPokemons(): array
    {

        return $this->cache->get('allPoke', function (ItemInterface $item): array {
            $item->expiresAfter(60);

            $response = $this->client->request(
                'GET',
                'https://tyradex.vercel.app/api/v1/pokemon'
            );

            // Gestion d'erreur pour la requête HTTP
            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Erreur lors de la récupération des données des Pokémon. Veuillez réessayer dans quelques instants...');
            }

            $content = $response->toArray();
            return $content;
        });
    }

    public function getPokemonByType($type): array
    {
        return $this->cache->get('pokeByType', function (ItemInterface $item) use ($type): array {

            $item->expiresAfter(2);

            $response = $this->client->request(
                'GET',
                'https://tyradex.vercel.app/api/v1/pokemon'
            );

            // Gestion d'erreur pour la requête HTTP
            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Erreur lors de la récupération des données des Pokémon. Veuillez réessayer dans quelques instants...');
            }

            $content = $response->toArray();

            $pokemonsType = [];

            foreach ($content as $pokemon) {
                if (isset($pokemon['types'])) {
                    foreach ($pokemon['types'] as $types) {
                        if ($types['name'] === $type) {
                            $pokemonsType[] = $pokemon;
                            break;
                        }
                    }
                }
            }

            return $pokemonsType;
        });
    }

    public function getPokemonById($id): array
    {

        return $this->cache->get('pokeById', function (ItemInterface $item) use ($id): array {

            $item->expiresAfter(2);

            $response = $this->client->request(
                'GET',
                'https://tyradex.vercel.app/api/v1/pokemon'
            );

            // Gestion d'erreur pour la requête HTTP
            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Erreur lors de la récupération des données des Pokémon. Veuillez réessayer dans quelques instants...');
            }

            $content = $response->toArray();

            foreach ($content as $pokemon) {
                if ($pokemon['pokedex_id'] == $id) {
                    return $pokemon;
                }
            }
        });
    }
}

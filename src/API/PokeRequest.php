<?php

namespace App\API;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PokeRequest
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function getRandomPokemon(int $count): array
    {
        $response = $this->client->request(
            'GET',
            'https://tyradex.vercel.app/api/v1/pokemon'
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->toArray();

        shuffle($content);
        $randomPokemon = array_slice($content, 0, $count);

        return $randomPokemon;
    }

    public function getAllPokemons(): array
    {
        $response = $this->client->request(
            'GET',
            'https://tyradex.vercel.app/api/v1/pokemon'
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->toArray();
        return $content;
    }

    public function getPokemonByType($type): array
    {
        $response = $this->client->request(
            'GET',
            'https://tyradex.vercel.app/api/v1/pokemon'
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
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
    }

    public function getPokemonById($id): array
    {
        $response = $this->client->request(
            'GET',
            'https://tyradex.vercel.app/api/v1/pokemon'
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->toArray();

        foreach ($content as $pokemon) {
            if ($pokemon['pokedex_id'] == $id) {
                return $pokemon;
            }
        }
    }
}

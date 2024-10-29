<?php

namespace App\Controller\Pokemon;

use App\API\PokeRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pokemons')]
class PokemonController extends AbstractController
{
    #[Route('/list', name: 'app_all_pokemon')]
    public function showAllpokemon(PokeRequest $pokeRequest): Response
    {
        return $this->render('pokemon/show.html.twig', [
            'pokemons' => $pokeRequest->getAllPokemons(),
        ]);
    }

    #[Route('/details/{name}', name: 'app_pokemon_details')]
    public function showPokemonDetails(string $name, PokeRequest $pokeRequest): Response
    {

        return $this->render('pokemon/showDetails.html.twig', [
            'pokemon' => $pokeRequest->getPokemonByName($name),
        ]);
    }

    //TEMPLATE NON CREE ROUTE NON UTILISEE
    // #[Route('/pokemons/{type}', name: 'app_pokemon_type')]
    // public function showPokemonByType($type, PokeRequest $pokeRequest): Response
    // {
    //     return $this->render('pokemon/showType.html.twig', [
    //         'pokemons' => $pokeRequest->getPokemonByType($type),
    //     ]);
    // }

    #[Route('/generation/{generation}', name: 'app_pokemon_gen')]
    public function showPokemonByGen($generation, PokeRequest $pokeRequest): Response
    {
        return $this->render('pokemon/showGen.html.twig', [
            'pokemons' => $pokeRequest->getPokemonByGeneration($generation),
            'generation' => $generation,
        ]);
    }

    #[Route('/ranking', name: 'app_pokemon_ranking')]
    public function showPokemonRanking(PokeRequest $pokeRequest): Response
    {
        $pokemonStats = $pokeRequest->getPokemonStats(5);

        return $this->render('pokemon/showRanking.html.twig', [
            'pokemonStats' => $pokemonStats,
        ]);
    }

    #[Route('/ranking/generation/{generation}', name: 'app_pokemon_ranking_by_gen')]
    public function showPokemonRankingByGen(PokeRequest $pokeRequest, int $generation): Response
    {
        return $this->render('pokemon/showRanking.html.twig', [
            'pokemons' => $pokeRequest->getPokemonByGeneration($generation),
        ]);
    }
}

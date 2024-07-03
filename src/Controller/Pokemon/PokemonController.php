<?php

namespace App\Controller\Pokemon;

use App\API\PokeRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PokemonController extends AbstractController
{
    #[Route('/all-pokemon', name: 'app_all_pokemon')]
    public function showAllpokemon(PokeRequest $pokeRequest): Response
    {
        return $this->render('pokemon/show.html.twig', [
            'pokemons' => $pokeRequest->getAllPokemons(),
        ]);
    }

    #[Route('/pokemon-details/{id}', name: 'app_pokemon_details')]
    public function showPokemonDetails(int $id, PokeRequest $pokeRequest): Response
    {

        return $this->render('pokemon/showDetails.html.twig', [
            'pokemon' => $pokeRequest->getPokemonById($id),
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

    #[Route('/pokemons/generation-{generation}', name: 'app_pokemon_gen')]
    public function showPokemonByGen($generation, PokeRequest $pokeRequest): Response
    {
        return $this->render('pokemon/showGen.html.twig', [
            'pokemons' => $pokeRequest->getPokemonByGeneration($generation),
            'generation' => $generation,
        ]);
    }
}

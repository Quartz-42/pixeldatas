<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PokemonRepository $pokemonRepository): Response
    {
        // Récupérer 3 Pokémon aléatoires
        $randomPokemons = $pokemonRepository->getRandomPokemons(3);

        return $this->render('home/index.html.twig', [
            'randomPokemons' => $randomPokemons,
        ]);
    }

    #[Route('/legal', name: 'app_legal')]
    public function showLegal(): Response
    {
        return $this->render('shared/_legal.html.twig');
    }
}

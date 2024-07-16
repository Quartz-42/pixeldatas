<?php

namespace App\Controller;

use App\API\PokeRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PokeRequest $pokeRequest): Response
    {
        $randomPokemons = $pokeRequest->getRandomPokemon(3);

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

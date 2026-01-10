<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapController extends AbstractController
{
    public function __construct(
        private readonly PokemonRepository $pokemonRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    #[Route('/sitemap.xml', name: 'app_sitemap', defaults: ['_format' => 'xml'])]
    public function index(): Response
    {
        $urls = [];
        $hostname = $this->getParameter('app.hostname') ?? 'http://localhost:8000';

        // Page d'accueil
        $urls[] = [
            'loc' => $hostname.$this->urlGenerator->generate('app_home'),
            'changefreq' => 'weekly',
            'priority' => '1.0',
        ];

        // Liste des Pokémons
        $urls[] = [
            'loc' => $hostname.$this->urlGenerator->generate('app_pokemons'),
            'changefreq' => 'daily',
            'priority' => '0.9',
        ];

        // Pages de filtres par type
        $types = $this->pokemonRepository->findPokemonTypes();
        foreach ($types as $typeData) {
            if ($typeData['name']) {
                $urls[] = [
                    'loc' => $hostname.$this->urlGenerator->generate('app_pokemons_by_type', ['type' => $typeData['name']]),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }
        }

        // Pages de filtres par génération
        $generations = $this->pokemonRepository->findPokemonGenerations();
        foreach ($generations as $generation) {
            if ($generation) {
                $urls[] = [
                    'loc' => $hostname.$this->urlGenerator->generate('app_pokemons_by_generation', ['generation' => $generation]),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }
        }

        // Pages de détails des Pokémons
        $pokemons = $this->pokemonRepository->findAll();
        foreach ($pokemons as $pokemon) {
            $urls[] = [
                'loc' => $hostname.$this->urlGenerator->generate('app_pokemon_details', ['name' => $pokemon->getName()]),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        $response = new Response(
            $this->renderView('sitemap/index.xml.twig', ['urls' => $urls]),
            Response::HTTP_OK
        );

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}

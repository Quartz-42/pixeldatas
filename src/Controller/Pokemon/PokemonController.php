<?php

namespace App\Controller\Pokemon;

use App\Repository\PokemonRepository;
use App\Repository\PokevolutionRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/pokemons')]
class PokemonController extends AbstractController
{
    #[Route('/list', name: 'app_all_pokemon')]
    public function showAllPokemons(
        PokemonRepository $pokemonRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] string $query = null,
    ): Response {
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($pokemonRepository->findBySearchQueryBuilder($query)),
            $page,
            50
        );

        // Calculer les pages visibles
        $visiblePages = $this->getVisiblePages($pager);

        return $this->render('pokemon/show.html.twig', [
            'pokemons' => $pager,
            'visiblePages' => $visiblePages,
        ]);
    }

    private function getVisiblePages(Pagerfanta $pager): array
    {
        $currentPage = $pager->getCurrentPage();
        $nbPages = $pager->getNbPages();

        $pages = [];

        // Toujours afficher la première page
        $pages[] = 1;

        // Pages autour de la page actuelle (3 avant et 3 après)
        for ($i = max(2, $currentPage - 3); $i <= min($nbPages - 1, $currentPage + 3); $i++) {
            $pages[] = $i;
        }

        // Toujours afficher la dernière page
        if ($nbPages > 1) {
            $pages[] = $nbPages;
        }

        // Éliminer les doublons et maintenir l'ordre
        return array_values(array_unique($pages));
    }

    #[Route('/details/{name}', name: 'app_pokemon_details')]
    public function showPokemonDetails(
        PokemonRepository $pokemonRepository,
        PokevolutionRepository $pokevolutionRepository,
        string $name
    ): Response {
        $pokemon = $pokemonRepository->findOneBy(['name' => $name]);

        // Récupérer les évolutions du Pokémon
        $evolutions = $pokevolutionRepository->findOneBy(['pokemon' => $pokemon->getId()]);

        return $this->render('pokemon/show_details.html.twig', [
            'pokemon' => $pokemon,
            'evolutions' => $evolutions,
        ]);
    }


    #[Route('/generation/{generation}', name: 'app_pokemon_gen')]
    public function showPokemonByGen($generation, PokeRequest $pokeRequest): Response
    {
        return $this->render('pokemon/show_gen.html.twig', [
            'pokemons' => $pokeRequest->getPokemonByGeneration($generation),
        ]);
    }

    #[Route('/ranking', name: 'app_pokemon_ranking')]
    public function showPokemonRanking(PokeRequest $pokeRequest): Response
    {
        $pokemonStats = $pokeRequest->getPokemonStats(10);

        return $this->render('pokemon/show_ranking.html.twig', [
            'pokemonStats' => $pokemonStats,
        ]);
    }

    #[Route('/ranking/generation/{generation}', name: 'app_pokemon_ranking_by_gen')]
    public function showPokemonRankingByGen(PokeRequest $pokeRequest, int $generation): Response
    {
        return $this->render('pokemon/show_ranking.html.twig', [
            'pokemons' => $pokeRequest->getPokemonByGeneration($generation),
        ]);
    }
}

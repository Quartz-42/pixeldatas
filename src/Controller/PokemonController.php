<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use App\Repository\PokevolutionRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pokemons')]
class PokemonController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        // $this->session = $requestStack->getSession();
    }

    #[Route('/list', name: 'app_all_pokemon')]
    public function showAllPokemons(
        PokemonRepository $pokemonRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] ?string $query = null,
    ): Response {
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($pokemonRepository->findBySearchQueryBuilder($query)),
            $page,
            40
        );

        // Calculer les pages visibles
        $visiblePages = $this->getVisiblePages($pager);

        //recupérer la page en cours et stocker en session
        $session = $this->requestStack->getSession();
        $session->set('current_page', $pager->getCurrentPage());

        $pokemonTypes = $pokemonRepository->findPokemonTypes();
        $generations = $pokemonRepository->findPokemonGenerations();

        return $this->render('pokemon/show.html.twig', [
            'pokemons' => $pager,
            'visiblePages' => $visiblePages,
            'pokemonTypes' => $pokemonTypes,
            'generations' => $generations,
        ]);
    }

    #[Route('/details/{name}', name: 'app_pokemon_details')]
    public function showPokemonDetails(
        PokemonRepository $pokemonRepository,
        PokevolutionRepository $pokevolutionRepository,
        string $name,
    ): Response {
        $pokemon = $pokemonRepository->findOneBy(['name' => $name]);

        // Récupérer les évolutions du Pokémon
        $evolutions = $pokevolutionRepository->findOneBy(['pokemon' => $pokemon->getId()]);

        //recuperer la page en cours
        $currentPage = $this->getCurrentPage();

        return $this->render('pokemon/show_details.html.twig', [
            'pokemon' => $pokemon,
            'evolutions' => $evolutions,
            'currentPage' => $currentPage,
        ]);
    }

    #[Route('/generation/{generation}', name: 'app_pokemon_gen')]
    public function showPokemonByGen(
        int $generation,
        PokemonRepository $pokemonRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] ?string $query = null,
    ): Response {
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($pokemonRepository->getPokemonsByGenerationForSearch($generation, $query)),
            $page,
            40
        );

        $numberOfPokemons = count($pokemonRepository->getPokemonsByGeneration($generation));

        // Calculer les pages visibles
        $visiblePages = $this->getVisiblePages($pager);

        return $this->render('pokemon/show_gen.html.twig', [
            'pokemons' => $pager,
            'visiblePages' => $visiblePages,
            'generation' => $generation,
            'numberOfPokemons' => $numberOfPokemons,
        ]);
    }

    #[Route('/type/{type}', name: 'app_pokemon_type')]
    public function showPokemonByType(
        string $type,
        PokemonRepository $pokemonRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] ?string $query = null,
    ): Response {
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($pokemonRepository->getPokemonsByTypeForSearch($type, $query)),
            $page,
            40
        );

        //recuperer la page en cours
        $currentPage = $this->getCurrentPage();

        $numberOfPokemons = count($pokemonRepository->getPokemonsByType($type));

        // Calculer les pages visibles
        $visiblePages = $this->getVisiblePages($pager);

        return $this->render('pokemon/show_type.html.twig', [
            'pokemons' => $pager,
            'visiblePages' => $visiblePages,
            'type' => $type,
            'numberOfPokemons' => $numberOfPokemons,
            'currentPage' => $currentPage,
        ]);
    }

    #[Route('/{type}/card', name: 'app_type_show_card', methods: ['GET'])]
    public function showCard(string $type): Response
    {
        //recuperer la page en cours
        $currentPage = $this->getCurrentPage();

        return $this->render('pokemon/_card.html.twig', [
            'type' => $type,
            'currentPage' => $currentPage,
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
        for ($i = max(2, $currentPage - 3); $i <= min($nbPages - 1, $currentPage + 3); ++$i) {
            $pages[] = $i;
        }

        // Toujours afficher la dernière page
        if ($nbPages > 1) {
            $pages[] = $nbPages;
        }

        // Éliminer les doublons et maintenir l'ordre
        return array_values(array_unique($pages));
    }

    private function getCurrentPage(): int
    {
        $session = $this->requestStack->getSession();
        return $session->get('current_page', 1);
    }
}

<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use App\Repository\PokevolutionRepository;
use App\Repository\TypeRepository;
use App\Service\ChartBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pokemons')]
class PokemonController extends AbstractController
{
    #[Route('', name: 'app_pokemons')]
    #[Route('/type/{type}', name: 'app_pokemon_type')]
    #[Route('/generation/{generation}', name: 'app_pokemon_gen')]
    public function listPokemons(
        PokemonRepository $pokemonRepository,
        TypeRepository $typeRepository,
        ?string $type = null,
        ?int $generation = null,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] ?string $query = null,
    ): Response {
        // Validation des filtres
        if ($type && !$typeRepository->findOneBy(['name' => $type])) {
            throw $this->createNotFoundException('Ce type n\'existe pas.');
        }

        if ($generation && !$pokemonRepository->findOneBy(['generation' => $generation])) {
            throw $this->createNotFoundException('Cette génération n\'existe pas.');
        }

        // Construire le query builder selon le filtre
        if ($type) {
            $queryBuilder = $pokemonRepository->getPokemonsByTypeForSearch($type, $query);
        } elseif ($generation) {
            $queryBuilder = $pokemonRepository->getPokemonsByGenerationForSearch($generation, $query);
        } else {
            $queryBuilder = $pokemonRepository->findBySearchQueryBuilder($query);
        }

        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($queryBuilder),
            $page,
            48
        );

        // Calculer les pages visibles
        $visiblePages = $this->getVisiblePages($pager);

        // Données pour l'accordéon (uniquement sur la page principale)
        $filters = [
            'active' => [
                'type' => $type,
                'generation' => $generation,
            ],
            'available' => [
                'types' => (!$type && !$generation) ? $pokemonRepository->findPokemonTypes() : null,
                'generations' => (!$type && !$generation) ? $pokemonRepository->findPokemonGenerations() : null,
            ],
        ];

        return $this->render('pokemon/list.html.twig', [
            'pokemons' => $pager,
            'visiblePages' => $visiblePages,
            'filters' => $filters,
        ]);
    }

    #[Route('/{name}', name: 'app_pokemon_details')]
    public function showPokemonDetails(
        PokemonRepository $pokemonRepository,
        PokevolutionRepository $pokevolutionRepository,
        ChartBuilder $chartBuilder,
        string $name,
    ): Response {
        $pokemon = $pokemonRepository->findOneByNameWithRelations($name);

        if (!$pokemon) {
            throw $this->createNotFoundException('Erreur');
        }

        // recuperation du datas chart
        $chart = $chartBuilder->createChart($pokemon);

        // Récupérer les évolutions
        $evolutions = $pokemon->getPokevolutions()->first() ?: null;

        $evoliNames = [];
        if (133 === $pokemon->getPokedexId()) {
            $evoliNames = $pokevolutionRepository->findAllEvoliNames();
        }

        return $this->render('pokemon/show_details.html.twig', [
            'pokemon' => $pokemon,
            'evolutions' => $evolutions,
            'chart' => $chart,
            'evoliNames' => $evoliNames,
        ]);
    }

    // retourne le template de la card type pour le popover
    #[Route('/{type}/popover', name: 'app_type_show_card', methods: ['GET'])]
    public function showCard(string $type): Response
    {
        return $this->render('pokemon/_popover.html.twig', [
            'type' => $type,
        ]);
    }

    /**
     * @param Pagerfanta<mixed> $pager
     *
     * @return int[]
     */
    private function getVisiblePages(Pagerfanta $pager): array
    {
        $currentPage = $pager->getCurrentPage();

        $nbPages = $pager->getNbPages();

        $pages = [];

        // Toujours afficher la première page
        $pages[] = 1;

        // Pages autour de la page actuelle (2 avant et 2 après)
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
}

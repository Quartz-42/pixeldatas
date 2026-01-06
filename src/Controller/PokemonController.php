<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use App\Repository\PokevolutionRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/pokemons')]
class PokemonController extends AbstractController
{

    #[Route('/list', name: 'app_all_pokemon')]
    public function showAllPokemons(
        PokemonRepository $pokemonRepository,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] ?string $query = null,
    ): Response {
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($pokemonRepository->findBySearchQueryBuilder($query)),
            $page,
            48
        );

        // Calculer les pages visibles
        $visiblePages = $this->getVisiblePages($pager);

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
        ChartBuilderInterface $chartBuilder
    ): Response {
        $pokemon = $pokemonRepository->findOneBy(['name' => $name]);

        $chart = $chartBuilder
            ->createChart(Chart::TYPE_POLAR_AREA)
            ->setData([
                'labels' => ['PV', 'ATK', 'DEF', 'ATK SPE', 'DEF SPE', 'Vitesse'],
                'datasets' => [
                    [
                        'label' => 'Stats',
                        'data' => [
                            $pokemon->getHp(),
                            $pokemon->getAtk(),
                            $pokemon->getDef(),
                            $pokemon->getSpeAtk(),
                            $pokemon->getSpeDef(),
                            $pokemon->getVit(),
                        ],
                        'backgroundColor' => [
                            'rgba(255, 0, 0, 0.6)',
                            'rgba(0, 128, 0, 0.6)',
                            'rgba(0, 0, 255, 0.6)',
                            'rgba(128, 0, 128, 0.6)',
                            'rgba(255, 165, 0, 0.6)',
                            'rgba(0, 255, 255, 0.6)'
                        ],
                        'borderColor' => [
                            'rgba(255, 0, 0, 1)',
                            'rgba(0, 128, 0, 1)',
                            'rgba(0, 0, 255, 1)',
                            'rgba(128, 0, 128, 1)',
                            'rgba(255, 165, 0, 1)',
                            'rgba(0, 255, 255, 1)'
                        ],
                        'borderWidth' => 1
                    ],
                ],
            ])
            ->setOptions([
                'responsive' => true,
                'maintainAspectRatio' => true,
                'animation' => [
                    'duration' => 0,
                ],
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'position' => 'top',
                    ],
                    'tooltip' => [
                        'enabled' => true,
                        'titleFont' => [
                            'size' => 16, // Augmenter la taille de la police du titre
                            'weight' => 'bold',
                        ],
                        'bodyFont' => [
                            'size' => 14, // Augmenter la taille de la police du corps
                        ],
                    ],
                    'datalabels' => [
                        'display' => true,
                        'color' => 'black',
                        'font' => [
                            'size' => 18, // Augmenter la taille des datalabels
                            'weight' => 'bold',
                        ],
                    ],
                ],
                'scales' => [
                    'r' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'display' => true,
                            'font' => [
                                'size' => 14,
                            ],
                        ],
                    ],
                ],
            ]);

        // Récupérer les évolutions du Pokémon
        $evolutions = $pokevolutionRepository->findOneBy(['pokemon' => $pokemon->getId()]);

        return $this->render('pokemon/show_details.html.twig', [
            'pokemon' => $pokemon,
            'evolutions' => $evolutions,
            'chart' => $chart,
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
            42
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
            42
        );

        $numberOfPokemons = count($pokemonRepository->getPokemonsByType($type));

        // Calculer les pages visibles
        $visiblePages = $this->getVisiblePages($pager);

        return $this->render('pokemon/show_type.html.twig', [
            'pokemons' => $pager,
            'visiblePages' => $visiblePages,
            'type' => $type,
            'numberOfPokemons' => $numberOfPokemons,
        ]);
    }

    #[Route('/{type}/card', name: 'app_type_show_card', methods: ['GET'])]
    public function showCard(string $type): Response
    {

        return $this->render('pokemon/_card.html.twig', [
            'type' => $type,
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
}

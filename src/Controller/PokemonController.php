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
            ->createChart(Chart::TYPE_BAR)
            ->setData([
                'labels' => ['PV', 'Attaque', 'Défense', 'Atq. Spé.', 'Déf. Spé.', 'Vitesse'],
                'datasets' => [
                    [
                        'data' => [
                            $pokemon->getHp(),
                            $pokemon->getAtk(),
                            $pokemon->getDef(),
                            $pokemon->getSpeAtk(),
                            $pokemon->getSpeDef(),
                            $pokemon->getVit(),
                        ],
                        'backgroundColor' => [
                            'rgba(239, 68, 68, 0.8)',  // Rouge (PV)
                            'rgba(249, 115, 22, 0.8)', // Orange (ATK)
                            'rgba(234, 179, 8, 0.8)',  // Jaune (DEF)
                            'rgba(59, 130, 246, 0.8)', // Bleu (SPE ATK)
                            'rgba(34, 197, 94, 0.8)',  // Vert (SPE DEF)
                            'rgba(168, 85, 247, 0.8)', // Mauve (VIT)
                        ],
                        'borderColor' => [
                            'rgba(220, 38, 38, 1)',
                            'rgba(234, 88, 12, 1)',
                            'rgba(202, 138, 4, 1)',
                            'rgba(37, 99, 235, 1)',
                            'rgba(22, 163, 74, 1)',
                            'rgba(147, 51, 234, 1)',
                        ],
                        'borderWidth' => 2,
                        'borderRadius' => 6,
                        'barPercentage' => 0.5, 
                    ],
                ],
            ])
            ->setOptions([
                'indexAxis' => 'y',
                'responsive' => true,
                'maintainAspectRatio' => false,
                'animation' => false,
                'plugins' => [
                    'legend' => [
                        'display' => false,
                    ],
                    'datalabels' => [
                        'display' => true,
                        'color' => 'black',
                        'anchor' => 'end',
                        'align' => 'end',
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold',
                        ],
                        'formatter' => function($value) {
                            return $value;
                        }
                    ],
                    'tooltip' => [
                        'enabled' => true,
                        'callbacks' => [
                           'title' => function () { return ''; },
                        ]
                    ],
                ],
                'scales' => [
                    'x' => [
                        'display' => false,
                        'max' => 260,
                        'beginAtZero' => true,
                    ],
                    'y' => [
                        'grid' => [
                            'display' => false,
                        ],
                        'ticks' => [
                            'font' => [
                                'size' => 14,
                                'weight' => 'bold',
                            ],
                            'color' => '#374151'
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

        // Calculer les pages visibles
        $visiblePages = $this->getVisiblePages($pager);

        return $this->render('pokemon/show_gen.html.twig', [
            'pokemons' => $pager,
            'visiblePages' => $visiblePages,
            'generation' => $generation,
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

        // Calculer les pages visibles
        $visiblePages = $this->getVisiblePages($pager);

        return $this->render('pokemon/show_type.html.twig', [
            'pokemons' => $pager,
            'visiblePages' => $visiblePages,
            'type' => $type,
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

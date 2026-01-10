<?php

namespace App\Service;

use App\Entity\Pokemon;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartBuilder
{
    public function __construct(
        private readonly ChartBuilderInterface $chartBuilder,
    ) {
    }

    public function createChart(Pokemon $pokemon): Chart
    {
        return $this->chartBuilder
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
                        'formatter' => function ($value) {
                            return $value;
                        },
                    ],
                    'tooltip' => [
                        'enabled' => true,
                        'callbacks' => [
                            'title' => function () {
                                return '';
                            },
                        ],
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
                            'color' => '#374151',
                        ],
                    ],
                ],
            ]);
    }
}

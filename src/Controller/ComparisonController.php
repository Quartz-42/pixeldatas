<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ComparisonController extends AbstractController
{
    #[Route('/comparison', name: 'app_comparison')]
    public function index(RequestStack $requestStack, PokemonRepository $pokemonRepository): Response
    {
        $session = $requestStack->getSession();
        $comparisonIds = $session->get('comparison', []);

        if (empty($comparisonIds)) {
            $pokemons = [];
        } else {
            $pokemons = $pokemonRepository->findBy(['id' => $comparisonIds]);
        }

        return $this->render('comparison/index.html.twig', [
            'pokemons' => $pokemons,
        ]);
    }

    #[Route('/comparison/add/{id}', name: 'app_comparison_add')]
    public function add(int $id, RequestStack $requestStack, Request $request): Response
    {
        $session = $requestStack->getSession();
        $comparison = $session->get('comparison', []);

        // Vérification doublon
        if (!in_array($id, $comparison)) {
            // Vérification limite (4 max)
            if (count($comparison) >= 4) {
                $this->addFlash('warning', 'Vous ne pouvez comparer que 4 Pokémons maximum');
            } else {
                $comparison[] = $id;
                $session->set('comparison', $comparison);
                $this->addFlash('success', 'Le Pokémon a été ajouté au comparateur !');
            }
        } else {
            $this->addFlash('info', 'Ce Pokémon est déjà dans le comparateur');
        }

        // Redirection vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/comparison/remove/{id}', name: 'app_comparison_remove')]
    public function remove(int $id, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $comparison = $session->get('comparison', []);

        // On retire l'ID du tableau
        if (($key = array_search($id, $comparison)) !== false) {
            unset($comparison[$key]);
            // On réindexe le tableau pour éviter les trous
            $session->set('comparison', array_values($comparison));
            $this->addFlash('success', 'Le Pokémon a été retiré du comparateur.');
        }

        return $this->redirectToRoute('app_comparison');
    }
}

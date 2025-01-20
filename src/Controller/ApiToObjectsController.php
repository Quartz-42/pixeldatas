<?php

namespace App\Controller;

use App\API\PokeRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiToObjectsController extends AbstractController
{
    // #[Route('/generate', name: 'api_to_objects', methods: ['GET'])]
    // public function fromApiToObjects(PokeRequest $pokeRequest): JsonResponse
    // {
    //     try {
    //         $pokeRequest->fromAPiToObjects();

    //         return new JsonResponse([
    //             'status' => 'success',
    //             'message' => 'Data successfully fetched and processed.',
    //         ], JsonResponse::HTTP_OK);
    //     } catch (\Exception $e) {
    //         // Gestion des erreurs
    //         return new JsonResponse([
    //             'status' => 'error',
    //             'message' => 'An error occurred while processing the data.',
    //             'details' => $e->getMessage(),
    //         ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }
}

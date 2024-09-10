<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GameController extends AbstractController
{
    #[Route('/snake', name: 'app_game_snake')]
    public function playSnake(): Response
    {
        return $this->render('game/snake.html');
    }

    #[Route('/p4', name: 'app_game_p4')]
    public function playP4(): Response
    {
        return $this->render('game/p4.html');
    }
}

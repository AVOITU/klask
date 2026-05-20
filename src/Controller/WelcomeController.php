<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WelcomeController extends AbstractController
{
    #[Route('/bienvenue', name: 'app_bienvenue', methods: ['GET'])]
    public function index(): Response
    {
        $student = $this->getUser();
        if (!$student instanceof User) {
            return $this->redirectToRoute('app_inscription_show');
        }

        return $this->render('bienvenue/bienvenue.html.twig', [
            'pseudo' => $student->getPseudo() ?? '',
        ]);
    }
}

<?php

namespace App\Controller\Impl;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Controller\SecurityController;

class SecurityControllerImpl extends AbstractController implements SecurityController
{
    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        // On fera le vrai formulaire de connexion plus tard !
        return new Response('Page de connexion à venir...');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony intercepte cette route tout seul grâce au security.yaml,
        // le code à l'intérieur ne sera jamais exécuté.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
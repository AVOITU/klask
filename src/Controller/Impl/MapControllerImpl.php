<?php

namespace App\Controller\Impl;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use App\Controller\MapController;
use App\Service\MapService;
use App\Service\SidebarUserMapService;
use App\Entity\User;

class MapControllerImpl extends AbstractController implements MapController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MapService $mapService,
        private readonly SidebarUserMapService $sidebarService,
    ) {
    }

    #[Route('/map', name: 'app_map')]
    public function index(): Response
    {
        $spheres = $this->mapService->getPreparedSpheres();
        $user = $this->getUser();
        
        // On récupère les stats (score, etc.) via ton service
        $userStats = null;
        if ($user instanceof User) {
            // Ton service utilise l'ID de l'utilisateur connecté pour calculer les scores
            $userStats = $this->sidebarService->createUserDTOById($user->getId());
        }

        // On envoie TOUT (les sphères ET les infos de la sidebar) à la MÊME vue Twig
        return $this->render('map/index.html.twig', [
            'spheres' => $spheres,
            'currentUser' => $user,
            'userStats' => $userStats // Le DTO de ton service
        ]);
    }
}
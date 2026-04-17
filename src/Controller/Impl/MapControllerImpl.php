<?php

namespace App\Controller\Impl;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use App\Controller\MapController;
use App\Service\MapService; // N'oublie pas d'importer l'interface du service !

class MapControllerImpl extends AbstractController implements MapController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MapService $mapService // On injecte NOTRE service ici
    ) {
    }

    #[Route('/map', name: 'app_map')]
    public function index(): Response
    {
        // On demande au service de faire tout le travail !
        $spheres = $this->mapService->getPreparedSpheres();

        // On envoie les données formatées à la vue Twig
        return $this->render('map/index.html.twig', [
            'spheres' => $spheres
        ]);
    }
}
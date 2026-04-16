<?php

namespace App\Controller\Impl;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;

use App\Controller\MapController;

class MapControllerImpl extends AbstractController implements MapController
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route('/map', name: 'app_map')]
    public function index(): Response
    {
        return $this->render('map/index.html.twig');
    }
}

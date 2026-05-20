<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ACCOMPANYING')]
class InstructionsController extends AbstractController
{
    // clé session partagée avec MapController via cette constante publique
    public const SESSION_INSTRUCTIONS_SEEN = 'accompanying_instructions_seen';

    public function __construct(private readonly RequestStack $requestStack) {}

    #[Route('/instructions', name: 'app_instructions', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('instructions/instructions.html.twig');
    }

    // marque les instructions comme lues puis redirige vers la map
    #[Route('/instructions/map', name: 'app_instructions_map', methods: ['GET'])]
    public function goToMap(): Response
    {
        $this->requestStack->getSession()->set(self::SESSION_INSTRUCTIONS_SEEN, true);

        return $this->redirectToRoute('app_map');
    }
}

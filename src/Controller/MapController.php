<?php

namespace App\Controller;

use App\Entity\User;
// use App\Repository\UserRepository; // injecté mais non utilisé
use App\Service\MapService;
use App\Service\SidebarUserMapService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MapController extends AbstractController
{
    public function __construct(
        private readonly MapService $mapService,
        private readonly SidebarUserMapService $sidebarService,
        // private readonly UserRepository $userRepository, à garder?
        private readonly EntityManagerInterface $em,
        private readonly RequestStack $requestStack,
    ) {
    }

    #[Route('/map', name: 'app_map', methods: ['GET'])]
    public function index(): Response
    {
        $sessionUser = $this->getUser();
        $userStats   = null;
        $currentUser = $sessionUser;

        if (
            $sessionUser instanceof User
            && in_array('ROLE_ACCOMPANYING', $sessionUser->getRoles(), true)
            && !$this->requestStack->getSession()->get(InstructionsController::SESSION_INSTRUCTIONS_SEEN)
        ) {
            return $this->redirectToRoute('app_instructions');
        }

        if ($sessionUser instanceof User) {
            $userStats = $this->sidebarService->createUserDTOById($sessionUser->getId());

            // Le DTO contient un User déjà chargé avec group + establissements (JOIN eager)
            if ($userStats !== null) {
                $currentUser = $userStats->getUser();
            }
        }

        // données carte embarquées dans le HTML
        return $this->render('map/map.html.twig', [
            'currentUser' => $currentUser,
            'userStats'   => $userStats,
            'spheresJson' => json_encode($this->mapService->getPreparedSpheres()),
        ]);
    }

    // /map/data non appelé depuis map.js (données embarquées via spheresJson dans map.html.twig) - route morte depuis l'optimisation JSON embarqué
    // À réactiver si un client externe a besoin de l'API carte
    // #[Route('/map/data', name: 'app_map_data', methods: ['GET'])]
    // public function data(): JsonResponse
    // {
    //     $response = $this->json($this->mapService->getPreparedSpheres());
    //     $response->setMaxAge(300);
    //     return $response;
    // }

    //étudiant vérifie si accompagnateur a envoyé un poke
    // Si oui, retourne poked=true ET efface le pokedAt pour ne pas re notifier
    #[Route('/map/poke-check', name: 'app_map_poke_check', methods: ['GET'])]
    public function pokeCheck(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User || $user->getPokedAt() === null) {
            return $this->json(['poked' => false]);
        }

        $user->setPokedAt(null);
        $this->em->flush();

        return $this->json(['poked' => true]);
    }
}

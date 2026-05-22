<?php

namespace App\Controller;

use App\Entity\User;
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
    public const SESSION_INTRO_ACCOMPANYING = 'accompanying_instructions_seen';
    public const SESSION_INTRO_STUDENT = 'student_guide_seen';
    private const IMG_ACCOMPANYING = 'images/instructions_accompanying.webp';
    private const IMG_STUDENT = 'images/instructions_student.webp';

    public function __construct(
        private readonly MapService $mapService,
        private readonly SidebarUserMapService $sidebarService,
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
        $showIntro         = false;
        $instructionImage  = null;

        if ($sessionUser instanceof User) {
            $roles   = $sessionUser->getRoles();
            $session = $this->requestStack->getSession();

            if (in_array('ROLE_ACCOMPANYING', $roles, true)) {
                $instructionImage = self::IMG_ACCOMPANYING;
                $showIntro        = !$session->get(self::SESSION_INTRO_ACCOMPANYING);
            } elseif (in_array('ROLE_STUDENT', $roles, true)) {
                $instructionImage = self::IMG_STUDENT;
                $showIntro        = !$session->get(self::SESSION_INTRO_STUDENT);
            }

            $userStats = $this->sidebarService->createUserDTOById($sessionUser->getId());

            if ($userStats !== null) {
                $currentUser = $userStats->getUser();
            }
        }

        return $this->render('map/map.html.twig', [
            'currentUser' => $currentUser,
            'userStats'   => $userStats,
            'spheresJson' => json_encode($this->mapService->getPreparedSpheres()),
            'showIntro'        => $showIntro,
            'instructionImage' => $instructionImage,
        ]);
    }

    #[Route('/map/intro/ack', name: 'app_map_intro_ack', methods: ['POST'])]
    public function ackIntro(): JsonResponse
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            $roles   = $user->getRoles();
            $session = $this->requestStack->getSession();

            if (in_array('ROLE_ACCOMPANYING', $roles, true)) {
                $session->set(self::SESSION_INTRO_ACCOMPANYING, true);
            } elseif (in_array('ROLE_STUDENT', $roles, true)) {
                $session->set(self::SESSION_INTRO_STUDENT, true);
            }
        }

        return $this->json(['ok' => true]);
    }

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

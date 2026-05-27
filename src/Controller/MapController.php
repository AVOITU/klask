<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MapService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MapController extends AbstractController
{
    private const SESSION_INTRO_ACCOMPANYING = 'accompanying_instructions_seen';
    private const SESSION_INTRO_STUDENT      = 'student_guide_seen';
    private const IMG_ACCOMPANYING           = 'images/instructions_accompanying.webp';
    private const IMG_STUDENT                = 'images/instructions_student.webp';

    public function __construct(
        private readonly MapService $mapService,
        private readonly UserService $userService,
        private readonly RequestStack $requestStack,
    ) {}

    #[Route('/map', name: 'app_map', methods: ['GET'])]
    public function index(): Response
    {
        $user            = $this->getUser();
        $userStats       = null;
        $showIntro       = false;
        $introImage      = null;
        $topSphereIds    = [];
        $bottomSphereIds = [];

        if ($user instanceof User) {
            $roles   = $user->getRoles();
            $session = $this->requestStack->getSession();

            if (in_array('ROLE_ACCOMPANYING', $roles, true)) {
                $introImage = self::IMG_ACCOMPANYING;
                $showIntro  = !$session->get(self::SESSION_INTRO_ACCOMPANYING);
            } elseif (in_array('ROLE_STUDENT', $roles, true)) {
                $topSphereIds = $this->userService->getTopSphereIds($user);
                if (empty($topSphereIds)) {
                    return $this->redirectToRoute('app_questionnaire');
                }
                $introImage      = self::IMG_STUDENT;
                $showIntro       = !$session->get(self::SESSION_INTRO_STUDENT);
                $bottomSphereIds = $this->userService->getBottomSphereIds($user);
            }

            $userStats = $this->userService->createUserDTOById($user->getId());
        }

        return $this->render('map/map.html.twig', [
            'currentUser'      => $userStats?->getUser() ?? $user,
            'userStats'        => $userStats,
            'spheresJson'      => json_encode($this->mapService->getPreparedSpheres()),
            'topSpheresJson'    => json_encode($topSphereIds),
            'bottomSpheresJson' => json_encode($bottomSphereIds),
            'showIntro'        => $showIntro,
            'instructionImage' => $introImage,
        ]);
    }

    #[Route('/map/intro/ack', name: 'app_map_intro_ack', methods: ['POST'])]
    public function ackIntro(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->json(['ok' => false]);
        }

        $roles   = $user->getRoles();
        $session = $this->requestStack->getSession();

        if (in_array('ROLE_ACCOMPANYING', $roles, true)) {
            $session->set(self::SESSION_INTRO_ACCOMPANYING, true);
        } elseif (in_array('ROLE_STUDENT', $roles, true)) {
            $session->set(self::SESSION_INTRO_STUDENT, true);
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

        $this->userService->clearPoke($user);

        return $this->json(['poked' => true]);
    }
}

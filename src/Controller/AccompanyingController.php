<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ACCOMPANYING')]
class AccompanyingController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    #[Route('/accompanying/group-scores', name: 'app_accompanying_group_scores', methods: ['GET'])]
    public function groupScores(): JsonResponse
    {
        $user      = $this->getUser();
        $groupCode = $user instanceof User ? $user->getGroupCode() : null;

        if ($groupCode === null) {
            return $this->json(['error' => 'Aucun groupe assigné.'], 400);
        }

        return $this->json($this->userService->getStudentScoresByGroupCode($groupCode));
    }

    #[Route('/accompanying/poke/{id}', name: 'app_accompanying_poke', methods: ['POST'])]
    public function poke(int $id): JsonResponse
    {
        $user      = $this->getUser();
        $groupCode = $user instanceof User ? $user->getGroupCode() : null;
        $student   = $this->userService->findById($id);

        if ($student === null) {
            return $this->json(['error' => 'Élève introuvable.'], 404);
        }

        if ($student->getGroup()?->getCode() !== $groupCode) {
            return $this->json(['error' => 'Élève hors de votre groupe.'], 403);
        }

        $this->userService->pokeStudent($student);

        return $this->json(['ok' => true]);
    }
}

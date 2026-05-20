<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ACCOMPANYING')]
class AccompanyingController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em,
    ) {
    }

    // Scores en temps réel
    #[Route('/accompanying/group-scores', name: 'app_accompanying_group_scores', methods: ['GET'])]
    public function groupScores(): JsonResponse
    {
        $accompanying = $this->getUser();
        if (!$accompanying instanceof User) {
            return $this->json(['error' => 'Non authentifié.'], 401);
        }

        $groupCode = $accompanying->getGroupCode();
        if ($groupCode === null) {
            return $this->json(['error' => 'Aucun groupe assigné.'], 400);
        }

        return $this->json($this->userRepository->findStudentScoresByGroupCode($groupCode));
    }

    // Signal poke à un élève
    #[Route('/accompanying/poke/{id}', name: 'app_accompanying_poke', methods: ['POST'])]
    public function poke(int $id): JsonResponse
    {
        $accompanying = $this->getUser();
        if (!$accompanying instanceof User) {
            return $this->json(['error' => 'Non authentifié.'], 401);
        }

        $groupCode = $accompanying->getGroupCode();

        $student = $this->userRepository->find($id);

        if ($student === null) {
            return $this->json(['error' => 'Élève introuvable.'], 404);
        }

        // Vérifie que l'élève appartient bien au groupe de l'accompagnateur
        if ($student->getGroup()?->getCode() !== $groupCode) {
            return $this->json(['error' => 'Élève hors de votre groupe.'], 403);
        }

        $student->setPokedAt(new \DateTimeImmutable());
        $this->em->flush();

        return $this->json(['ok' => true]);
    }
}

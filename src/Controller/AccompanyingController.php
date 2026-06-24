<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

#[IsGranted('ROLE_ACCOMPANYING')]
class AccompanyingController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
    )
    {
    }

    #[Route('/accompanying/group-scores', name: 'app_accompanying_group_scores', methods: ['GET'])]
    public function groupScores(): JsonResponse
    {
        $user = $this->getUser();
        $groupCode = $user instanceof User ? $user->getGroupCode() : null;

        if ($groupCode === null) {
            return $this->json(['error' => 'Aucun groupe assigné.'], 400);
        }

        return $this->json($this->userService->getStudentScoresByGroupCode($groupCode));
    }

    #[Route('/accompanying/poke/{id}', name: 'app_accompanying_poke', methods: ['POST'])]
    public function poke(int $id): JsonResponse
    {
        $user = $this->getUser();
        $groupCode = $user instanceof User ? $user->getGroupCode() : null;
        $student = $this->userService->findById($id);

        if ($student === null) {
            return $this->json(['error' => 'Élève introuvable.'], 404);
        }

        if ($student->getGroup()?->getCode() !== $groupCode) {
            return $this->json(['error' => 'Élève hors de votre groupe.'], 403);
        }

        $this->userService->pokeStudent($student);

        return $this->json(['ok' => true]);
    }

// Pour l'instant, j'ai mis là pour le dashboard accompagnateur, à voir si on fait un AccompanyingDashboardController
#[Route('/espace-accompagnateur', name: 'app_accompanying_dashboard', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $group = $user->getGroup(); // Récupère l'entité Group de l'accompagnateur

        $students = [];
        $totalScore = 0;

        if ($group) {
            // On récupère tous les utilisateurs qui appartiennent à ce groupe
            $students = $userRepository->findBy(['group' => $group]);

            // On calcule le score collectif du groupe
            foreach ($students as $student) {
                // On évite de compter les points de l'accompagnateur lui-même s'il est dans la liste
                if ($student !== $user) {
                    $totalScore += $student->getScore() ?? 0;
                }
            }
        }

        // On envoie TOUTES les variables nécessaires au template Twig
        return $this->render('accompanying_dashboard/index.html.twig', [
            'accompanying' => $user,
            'group' => $group,
            'students' => $students,
            'totalScore' => $totalScore,
        ]);

    }

    #[Route('/test-mercure', name: 'test_mercure')]
    public function testMercure(): Response
    {
        $hubUrl = 'http://127.0.0.1:3000/.well-known/mercure';

        // 1. Récupération directe de la clé depuis les variables d'environnement (.env)
        $secretKey = $_ENV['MERCURE_JWT_SECRET'] ?? $_SERVER['MERCURE_JWT_SECRET'] ?? '!ChangeThisMercureHubJWTSecretKey!';

        // 2. On crée manuellement le Token JWT attendu par le Hub Mercure
        $signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();
        $signingKey = \Lcobucci\JWT\Signer\Key\InMemory::plainText($secretKey);

        $token = \Lcobucci\JWT\Configuration::forSymmetricSigner($signer, $signingKey)
            ->builder()
            ->withClaim('mercure', ['publish' => ['*']])
            ->getToken($signer, $signingKey)
            ->toString();

        // 3. Les données à envoyer
        $data = [
            'topic' => 'https://klask.fr/groupe/test',
            'data'  => json_encode([
                'message' => 'ALERTE MERCURE : Le score vient de changer !',
                'score' => 100
            ])
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
                    "Authorization: Bearer " . $token . "\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
                'ignore_errors' => true
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ];

        $context = stream_context_create($options);
        $result  = @file_get_contents($hubUrl, false, $context);

        if ($result === false) {
            return new Response("Impossible de joindre le Hub.", 500);
        }

        return new Response('Message envoyé au Hub Mercure avec succès ! (Réponse Hub : ' . htmlspecialchars($result) . ')');
    }
}

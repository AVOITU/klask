<?php

namespace App\Controller\Impl;

use App\Controller\ScoreController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class ScoreControllerImpl extends AbstractController implements ScoreController
{

    #[Route('/score', name: 'app_score')]
    public function score(): Response
    {
        // Mock des scores (comme si ça venait de la BDD)
        $scores = [
            ['name' => 'Alice', 'score' => 120],
            ['name' => 'Bob', 'score' => 100],
            ['name' => 'Charlie', 'score' => 90],
            ['name' => 'David', 'score' => 80],
            ['name' => 'Eve', 'score' => 70],
            ['name' => 'Frank', 'score' => 60],
            ['name' => 'Grace', 'score' => 110],
            ['name' => 'Hugo', 'score' => 95],
            ['name' => 'Ines', 'score' => 85],
            ['name' => 'Jack', 'score' => 75],
        ];

        // Trier les scores décroissants
        usort($scores, fn($a, $b) => $b['score'] <=> $a['score']);

        // Prendre le gagnant
        $winner = $scores[0] ?? null;

        // Prendre le top 5
        $topScores = array_slice($scores, 0, 5);

        // Mock utilisateur courant
        $currentUserName = 'Charlie';
        $userScore = null;
        foreach ($scores as $score) {
            if ($score['name'] === $currentUserName) {
                $userScore = $score;
                break;
            }
        }
        $groupTotal = array_reduce(
            $scores,
            fn($carry, $item) => $carry + $item['score'],
            0
        );

        return $this->render('/score/score.html.twig', [
            'winner' => $winner,
            'topScores' => $topScores,
            'userScore' => $userScore,
            'groupTotal' => $groupTotal,
        ]);
    }
}
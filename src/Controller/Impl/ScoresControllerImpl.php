<?php

namespace Controller\Impl;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Controller\ScoresController;
use Service\ScoresService;

class ScoresControllerImpl implements ScoresController
{
    private ScoresService $scoresService;

    public function __construct(ScoresService $scoresService)
    {
        $this->scoresService = $scoresService;
    }

    public function showForm(): void
    {
        $teams = $this->scoresService->getScoresByClass();

        include __DIR__ . '/../../../templates/scores.php';
    }
}
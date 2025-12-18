<?php

namespace Controller\Impl;

use Controller\InscriptionController;
use Service\InscriptionService;

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

class InscriptionControllerImpl implements InscriptionController
{
    public function __construct(private InscriptionService $inscriptionService) {}

    public function showForm(): void
    {
        [$classes, $schools] = $this->inscriptionService->getAllSchoolsAndClasses();
        $nom_depart = $this->inscriptionService->generateDefaultNickname();
        require __DIR__ . '/../../../templates/formulaireklask.php';
    }

    public function submit(): void
    {
        [$messageSuccess, $messageError] =
            $this->inscriptionService->registerStudent($_POST);
        $this->showForm();
    }
}
<?php

namespace Controller\Impl;

use Controller\InscriptionController;
use Service\InscriptionService;

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../src/Repository/ClassRoomRepository.php';
require_once __DIR__ . '/../../../src/Repository/Impl/ClassRoomRepositoryImpl.php';
require_once __DIR__ . '/../../../src/Repository/UserRepository.php';
require_once __DIR__ . '/../../../src/Repository/Impl/UserRepositoryImpl.php';
require_once __DIR__ . '/../../../src/Service/InscriptionService.php';
require_once __DIR__ . '/../../../src/Service/Impl/InscriptionServiceImpl.php';
require_once __DIR__ . '/../../../src/Controller/InscriptionController.php';
require_once __DIR__ . '/../../../src/Controller/Impl/InscriptionControllerImpl.php';

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
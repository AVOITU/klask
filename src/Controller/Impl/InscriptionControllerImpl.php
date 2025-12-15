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
    public function __construct(private InscriptionService $service) {}

    public function handleRequest(): void
    {
        [$classes, $schools] = $this->service->getClassAndStudent();

        $messageSuccess = null;
        $messageError   = null;

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'POST') {
            [$messageSuccess, $messageError] = $this->service->handleRegistration($_POST);
        }

        $nom_depart = $this->service->generateDefaultNickname();

        require __DIR__ . '/../../../templates/formulaireklask.php';
    }
}

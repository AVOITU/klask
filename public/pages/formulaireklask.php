<?php

use Controller\Impl\InscriptionControllerImpl;
use Repository\Impl\ClassRoomRepositoryImpl;
use Repository\Impl\UserRepositoryImpl;
use Service\Impl\ClassRoomServiceImpl;
use Service\Impl\InscriptionServiceImpl;
use Service\Impl\UserServiceImpl;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

$pdo = get_pdo();

$classeRepo         = new ClassRoomRepositoryImpl($pdo);
$userRepo           = new UserRepositoryImpl($pdo);
$classRoomService   = new ClassRoomServiceImpl($classeRepo);
$userService        = new UserServiceImpl($userRepo);
$inscriptionService = new InscriptionServiceImpl($classRoomService, $userService);
$controller         = new InscriptionControllerImpl($inscriptionService);

$controller->showForm();
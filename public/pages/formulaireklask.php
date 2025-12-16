<?php

use Controller\Impl\InscriptionControllerImpl;
use Repository\Impl\ClassRoomRepositoryImpl;
use Repository\Impl\UserRepositoryImpl;
use Service\Impl\InscriptionServiceImpl;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Repository/Impl/ClassRoomRepositoryImpl.php';
require_once __DIR__ . '/../../src/Repository/Impl/UserRepositoryImpl.php';
require_once __DIR__ . '/../../src/Service/Impl/InscriptionServiceImpl.php';
require_once __DIR__ . '/../../src/Controller/Impl/InscriptionControllerImpl.php';

$pdo = get_pdo();

$classeRepo      = new ClassRoomRepositoryImpl($pdo);
$utilisateurRepo = new UserRepositoryImpl($pdo);
$service         = new InscriptionServiceImpl($classeRepo, $utilisateurRepo);
$controller      = new InscriptionControllerImpl($service);

$controller->showForm();
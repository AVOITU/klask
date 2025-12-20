<?php

use Controller\Impl\InscriptionControllerImpl;
use Repository\Impl\AuthorityRepositoryImpl;
use Repository\Impl\ClassRoomRepositoryImpl;
use Repository\Impl\UserRepositoryImpl;
use Service\Impl\AuthorityServiceImpl;
use Service\Impl\ClassRoomServiceImpl;
use Service\Impl\InscriptionServiceImpl;
use Service\Impl\UserServiceImpl;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

$pdo = get_pdo();

$classeRepo         = new ClassRoomRepositoryImpl($pdo);
$userRepo           = new UserRepositoryImpl($pdo);
$authorityRepo      = new AuthorityRepositoryImpl($pdo);
$classRoomService   = new ClassRoomServiceImpl($classeRepo);
$userService        = new UserServiceImpl($userRepo);
$authorityService   = new AuthorityServiceImpl($authorityRepo);
$inscriptionService = new InscriptionServiceImpl($classRoomService, $userService, $authorityService);
$controller         = new InscriptionControllerImpl($inscriptionService);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->submit();
} else {
    $controller->showForm();
}
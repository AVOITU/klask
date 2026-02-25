<?php

use Controller\Impl\ScoresControllerImpl;
use Repository\Impl\ClassRoomRepositoryImpl;
use Service\Impl\ClassRoomServiceImpl;
use Service\Impl\ScoresServiceImpl;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

$pdo = get_pdo();

$classeRepo             = new ClassRoomRepositoryImpl($pdo);
$classRoomService       = new ClassRoomServiceImpl($classeRepo);
$scoresService     = new ScoresServiceImpl($classRoomService);
$scoresController  = new ScoresControllerImpl($scoresService);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $scoresController->showForm();
}
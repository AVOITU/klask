<?php

use Controller\Impl\SidebarUserMapControllerImpl;
use Repository\Impl\UserRepositoryImpl;
use Service\Impl\SidebarUserMapServiceImpl;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

$pdo = get_pdo();

$userRepo = new UserRepositoryImpl ($pdo);
$sidebarUserMapService = new SidebarUserMapServiceImpl($userRepo);
$sidebarUserMapController = new SidebarUserMapControllerImpl($sidebarUserMapService);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// envoyer vers le get du controller de votre page
    $sidebarUserMapController->index();
} else {
    $sidebarUserMapController->index();
// post envoyer vers le post du controller de votre page
}
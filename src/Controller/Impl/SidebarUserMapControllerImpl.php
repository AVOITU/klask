<?php

namespace Controller\Impl;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Controller\SidebarUserMapController;
use Service\SidebarUserMapService;

/**
 * Controller :
 * - Reçoit la requête
 * - Appelle le Service
 * - Affiche la Vue
 */
class SidebarUserMapControllerImpl implements SidebarUserMapController
{
    private SidebarUserMapService $sidebarUserMapService;

    public function __construct(SidebarUserMapService $sidebarUserMapService)
    {
        $this->sidebarUserMapService = $sidebarUserMapService;
    }

    public function index(): void
    {
        $userId = isset($_GET['id_user']) ? (int)$_GET['id_user'] : 0;

        $currentUser = null;

        if ($userId > 0) {
            $currentUser = $this->sidebarUserMapService->getUserById($userId);
        }
    
        include __DIR__ . '$/../../../templates/sidebar_user_map.php';
    }
}
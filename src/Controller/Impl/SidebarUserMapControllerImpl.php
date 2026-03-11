<?php

namespace App\Controller\Impl;

use App\Service\SidebarUserMapService;
use App\Controller\SidebarUserMapController;

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
            $currentUser = $this->sidebarUserMapService->createUserDTOById($userId);
        }

        include __DIR__ . '/../../../templates/sidebar_user_map.html.twig';
    }
}

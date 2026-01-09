<?php

namespace Service;

use Model\User;

/**
 * Service :
 * Contient la logique applicative.
 */
interface SidebarUserMapService
{
    public function getUserById(int $idUser): ?User;
}
<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;

/**
 * Service :
 * Contient la logique applicative.
 */
interface SidebarUserMapService
{
    public function getUserById(int $idUser): ?User;
    public function createUserDTOById(int $idUser) :? UserDTO;
}

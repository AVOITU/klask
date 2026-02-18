<?php

namespace Service;

use DTO\UserDTO;
use Model\User;

/**
 * Service :
 * Contient la logique applicative.
 */
interface SidebarUserMapService
{
    public function getUserById(int $idUser): ?User;
    public function createUserDTOById(int $idUser) :? UserDTO;
}
<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\Student;

/**
 * Service :
 * Contient la logique applicative.
 */
interface SidebarUserMapService
{
    public function findUserWithClassAndAuthority(int $idUser): ?Student;
    public function createUserDTOById(int $idUser) :? UserDTO;
}

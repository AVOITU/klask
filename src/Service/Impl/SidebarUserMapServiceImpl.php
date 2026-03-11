<?php

namespace App\Service\Impl;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Service\SidebarUserMapService;
use App\Service\UserService;

/**
 * Implémentation concrète du Service.
 * Orchestration métier (pas d'accès direct à la BDD).
 */
class SidebarUserMapServiceImpl implements SidebarUserMapService
{

    // 1. On déclare la propriété pour le Repository
    private UserService $userService;

    // 2. On injecte le Repository via le constructeur
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // 3. La méthode qui fait le lien
    public function findUserWithClassAndAuthority(int $idUser): ?User
    {
        // On délègue le travail au Repository existant
        return $this->userService->findUserWithClassAndAuthority($idUser);
    }

    public function createUserDTOById(int $idUser) :? UserDTO
    {
        return $this->userService->findUserStats($idUser);
    }
}

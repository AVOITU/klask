<?php

namespace Service\Impl;

use Service\SidebarUserMapService;
use Repository\UserRepository;
use Model\User;

require_once __DIR__ . '/../../../vendor/autoload.php';

/**
 * Implémentation concrète du Service.
 * Orchestration métier (pas d'accès direct à la BDD).
 */
class SidebarUserMapServiceImpl implements SidebarUserMapService
{
    public function getAll(): array
    { return []; }

    // 1. On déclare la propriété pour le Repository
    private UserRepository $userRepository;

    // 2. On injecte le Repository via le constructeur
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // 3. La méthode qui fait le lien
    public function getUserById(int $id_user): ?User
    {
        // On délègue le travail au Repository existant
        return $this->userRepository->findById($id_user);
    }
}
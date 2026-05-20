<?php

namespace App\Service;

use App\DTO\UserDTO;

// délègue uniquement à UserService::createUserDTOById
// peut être supprimée en injectant UserService directement dans MapController? à voir
// si aucune logique propre à la sidebar
interface SidebarUserMapService
{
    // pas appelé en prod
    // public function findUserWithGroupAndAuthority(int $userId): ?User;

    public function createUserDTOById(int $userId): ?UserDTO;
}

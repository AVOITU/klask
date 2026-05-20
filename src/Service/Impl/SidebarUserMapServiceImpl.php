<?php

namespace App\Service\Impl;

use App\DTO\UserDTO;
use App\Service\SidebarUserMapService;
use App\Service\UserService;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
class SidebarUserMapServiceImpl implements SidebarUserMapService
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    // public function findUserWithGroupAndAuthority(int $userId): ?User
    // {
    //     return $this->userService->findUserWithGroupAndAuthority($userId);
    // }

    public function createUserDTOById(int $userId): ?UserDTO
    {
        return $this->userService->createUserDTOById($userId); 
    }
}

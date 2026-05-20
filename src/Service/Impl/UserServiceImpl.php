<?php

namespace App\Service\Impl;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\GroupService;
use App\Service\UserService;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
class UserServiceImpl implements UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly GroupService $groupService,
    ) {
    }

    public function insertStudent(User $student): User
    {
        return $this->userRepository->insertStudent($student);
    }

    // jamais appelé en prod mais à garder si besoin
    // public function findUserWithGroupAndAuthority(int $idUser): ?User
    // {
    //     return $this->userRepository->findUserWithGroupAndAuthority($idUser);
    // }

    public function createUserDTOById(int $idUser): ?UserDTO 
    {
        $dto = $this->findUserStats($idUser);

        if ($dto === null) {
            return null;
        }

        $groupId = $dto->getUser()->getGroup()?->getId();

        if ($groupId === null) {
            return $dto;
        }

        return $dto->withGroupTotalScore($this->groupService->findGroupTotalScore($groupId));
    }

    public function findUserStats(int $userId): ?UserDTO
    {
        return $this->userRepository->findUserStats($userId);
    }

    // doublon de GroupService::findGroupTotalScore
    // public function findGroupTotalScore(int $groupId): int
    // {
    //     return $this->groupService->findGroupTotalScore($groupId);
    // }
}

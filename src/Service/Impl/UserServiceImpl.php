<?php

namespace App\Service\Impl;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;

require_once __DIR__ . '/../../../vendor/autoload.php';
class UserServiceImpl implements UserService
{
    public function __construct( private UserRepository $userRepo) {}

    public function insertStudent($student): User
    { return $this->userRepo->insertStudent($student); }

    public function findUserWithClassAndAuthority(int $idUser) : ?User
    {
        return $this->userRepo->findUserWithClassAndAuthority($idUser);
    }

    public function createUserDTObyId(int $idUser): ?UserDTO
    {
        $dto = $this->findUserStats($idUser);
        if (!$dto) return null;

        $classId = $dto->getUser()->getClassRoom()->getIdClass();
        $classTotal = $this->findClassTotalScore($classId);

        return $dto->withClassTotalScore($classTotal);
    }

    public function findUserStats(int $userId) : ?UserDTO
    {
        return $this->userRepo->findUserStats($userId);
    }

    public function findClassTotalScore(int $classId): int
    {
        return $this->findClassTotalScore($classId);
    }
}

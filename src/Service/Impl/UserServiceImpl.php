<?php

namespace App\Service\Impl;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\GroupService;
use App\Service\UserService;

class UserServiceImpl implements UserService
{
    public function __construct( private UserRepository $userRepo,
                                 private  GroupService  $groupService) {}

    public function insertStudent($student): User
    { return $this->userRepo->insertStudent($student); }

    public function findUserWithGroupAndAuthority(int $idUser) : ?User
    { return $this->userRepo->findUserWithGroupAndAuthority($idUser); }

    public function createUserDTObyId(int $idUser): ?UserDTO
    {
        $dto = $this->findUserStats($idUser);
        if (!$dto) return null;

        //    findUserStats et findClassTotalScore =Requêtes scindées pour permettre à l'ORM de
        //    fonctionner correctement et d'éviter une mega requête
        $groupId = $dto->getUser()->getGroup()->getId();
        $groupTotal = $this->findGroupTotalScore($groupId);

        return $dto->withClassTotalScore($groupTotal);
    }

    public function findUserStats(int $userId) : ?UserDTO
    { return $this->userRepo->findUserStats($userId); }

    public function findGroupTotalScore(int $groupId): int
    { return $this->groupService->findGroupTotalScore($groupId); }
}

<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;

interface UserService
{
    public function insertStudent(User $student): User;
    // jamais appelé en prod
    // public function findUserWithGroupAndAuthority(int $idUser): ?User;
    public function createUserDTOById(int $idUser): ?UserDTO; // I8 — renommé (b→B)
    public function findUserStats(int $userId) : ?UserDTO;
    // findGroupTotalScore est un doublon de GroupService::findGroupTotalScore
    // public function findGroupTotalScore(int $groupId): int;
}

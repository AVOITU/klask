<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;

interface UserService
{
    public function insertStudent($student): User;
    public function findUserWithGroupAndAuthority(int $idUser) :?User;
    public function createUserDTObyId(int $idUser) :?UserDTO;
    public function findUserStats(int $userId) : ?UserDTO;
    public function findGroupTotalScore(int $groupId): int;
}

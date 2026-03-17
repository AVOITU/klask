<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;

interface UserService
{
    public function insertStudent($student): User;
    public function findUserWithClassAndAuthority(int $idUser) :?User;
    public function createUserDTObyId(int $idUser) :?UserDTO;
    public function findUserStats(int $userId) : ?UserDTO;
    public function findClassTotalScore(int $classId): int;
}

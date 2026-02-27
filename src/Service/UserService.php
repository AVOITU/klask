<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\Student;

interface UserService
{
    public function insertStudent($student): Student;
    public function findUserWithClassAndAuthority(int $idUser) :?Student;
    public function createUserDTObyId(int $idUser) :?UserDTO;
    public function findUserStats(int $userId) : ?UserDTO;
    public function findClassTotalScore(int $classId): int;
}

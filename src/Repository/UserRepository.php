<?php

namespace App\Repository;
use App\DTO\UserDTO;
use App\Entity\Student;

interface UserRepository
{
    public function findUserWithClassAndAuthority(int $id): ?Student;
    public function insertStudent(Student $user): Student;

    public function findUserStats(int $userId): ?UserDTO;

    public function findClassTotalScore(int $classId): int;
}

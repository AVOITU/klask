<?php

namespace App\Repository;
use App\DTO\UserDTO;
use App\Entity\User;

interface UserRepository
{
    public function findUserWithClassAndAuthority(int $id): ?User;
    public function insertStudent(User $user): User;

    public function findUserStats(int $userId): ?UserDTO;
}

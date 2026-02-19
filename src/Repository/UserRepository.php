<?php

namespace App\Repository;
use App\DTO\UserDTO;
use App\Entity\User;

interface UserRepository
{
    public function insertStudent(User $user): User;
    public function findById(int $idUser): ?User;

    public function createUserDTOById(int $idUser): ? UserDTO;
}

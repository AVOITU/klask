<?php

namespace Repository;
use DTO\UserDTO;
use Model\User;

interface UserRepository
{
    public function insertStudent(User $user): User;
    public function findById(int $idUser): ?User;

    public function createUserDTOById(int $idUser): ? UserDTO;
}
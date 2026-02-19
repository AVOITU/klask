<?php

namespace Service\Impl;

use DTO\UserDTO;
use Model\User;
use Repository\UserRepository;
use Service\UserService;

require_once __DIR__ . '/../../../vendor/autoload.php';
class UserServiceImpl implements UserService
{
    public function __construct( private UserRepository $userRepo) {}

    public function insertStudent($student): User
    { return $this->userRepo->insertStudent($student); }

    public function findById(int $idUser) : ?User
    {
        return $this->userRepo->findById($idUser);
    }

    public function createUserDTObyId(int $idUser) :?UserDTO
    {
        return $this->userRepo->createUserDTOById($idUser);
    }
}
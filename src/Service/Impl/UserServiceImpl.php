<?php

namespace Service\Impl;

use Model\User;
use Repository\UserRepository;
use Service\UserService;

require_once __DIR__ . '/../../../vendor/autoload.php';
class UserServiceImpl implements UserService
{
    public function __construct( private UserRepository $userRepo) {}

    public function insertStudent($student): User
    {
        return $this->userRepo->insertStudent($student);
    }
}
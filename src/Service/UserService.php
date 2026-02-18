<?php

namespace Service;

use DTO\UserDTO;
use Model\User;

interface UserService
{

    public function insertStudent($student): User;
    public function findById(int $idUser) :?User;
    public function createUserDTObyId(int $idUser) :?UserDTO;
}
<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;

interface UserService
{

    public function insertStudent($student): User;
    public function findById(int $idUser) :?User;
    public function createUserDTObyId(int $idUser) :?UserDTO;
}

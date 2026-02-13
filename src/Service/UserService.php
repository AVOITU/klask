<?php

namespace Service;

use Model\User;

interface UserService
{

    public function insertStudent($student): User;
    public function findById(int $idUser) :?User;
}
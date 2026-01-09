<?php

namespace Service;

use Model\User;

interface UserService
{

    public function findById(int $idUser) :?User;
}
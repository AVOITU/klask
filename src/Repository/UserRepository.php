<?php

namespace Repository;
use Model\User;

interface UserRepository
{
    public function insertStudent(User $user): User;
}
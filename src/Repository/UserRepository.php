<?php

namespace Repository;
interface UserRepository
{
    public function insertStudent(string $pseudo, int $id_classe): void;
}
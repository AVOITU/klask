<?php

namespace App\Repository;

use App\Entity\Authority;

interface AuthorityRepository
{
    public function findByRole(string $roleName) : ?Authority;
}

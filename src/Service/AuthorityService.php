<?php

namespace App\Service;

use App\Entity\Authority;

interface AuthorityService
{
    public function findByRole(string $role) : ?Authority;
}

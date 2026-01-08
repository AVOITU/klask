<?php

namespace Repository;

use Model\Authority;

interface AuthorityRepository
{
    public function findByRole(string $role) : ?Authority;
}
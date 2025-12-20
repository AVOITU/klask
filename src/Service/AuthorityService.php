<?php

namespace Service;

use Model\Authority;

interface AuthorityService
{
    public function findByRole(string $role) : ?Authority;
}
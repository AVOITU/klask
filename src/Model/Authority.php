<?php

namespace Model;


final class Authority
{
    public function __construct(
        public int    $idAuthority,
        public string $roleUser,
        public string $authorityUser,
        public User   $user, // (1,1)
    ) {}
}
<?php

namespace Model;

class ClassRoom
{
    /**
     * @param User[] $users
     */
    public function __construct(
        public int $idClass,
        public string $school,
        public string $nameClass,
        public array $users = [], // APPARTIENT (1,N)
    ) {}
}
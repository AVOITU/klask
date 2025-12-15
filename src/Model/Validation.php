<?php

namespace Model;

final class Validation
{
    public function __construct(
        public int $idValidation,
        public string $hourValidation,
        public Activity $activity, // ENREGISTRE (1,1)
        public User $user,         // VALIDE (1,1)
    ) {}
}
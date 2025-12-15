<?php

namespace Model;

final class User
{
    /**
     * @param Validation[] $validations
     * @param Authority[]  $authorities
     */
    public function __construct(
        public int $idUser,
        public string $pseudoUser,
        public ClassRoom $class,            // APPARTIENT (1,1)
        public array $validations = [],   // VALIDE (1,N)
        public array $authorities = [],   // (1,N)
    ) {}
}
<?php

namespace App\Service;
use App\Entity\User;

interface InscriptionService
{
    public function findDistinctEstablishments(): array;
    public function generateDefaultNickname(): string;
    public function registerStudent(User $student): User;
}

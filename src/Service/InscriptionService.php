<?php

namespace App\Service;

use App\Entity\Group;
use App\Entity\User;

interface InscriptionService
{
    public function generateUniquePseudo(): string;

    public function findGroupByCode(string $code): ?Group;

    public function registerStudent(User $student): User;
}

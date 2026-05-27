<?php

namespace App\Service;

use App\Entity\Establishment;
use App\Entity\Group;
use App\Entity\User;

interface InscriptionService
{
    public function generateUniquePseudo(): string;
    public function findGroupByCode(string $code): ?Group;
    public function validateGroupForRegistration(Group $group, Establishment $establishment, string $level): bool;
    public function isGroupFull(Group $group): bool;
    public function registerStudent(User $student): User;
}

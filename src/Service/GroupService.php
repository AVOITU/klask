<?php

namespace App\Service;

use App\Entity\Group;

interface GroupService
{
    public function findDistinctEstablishments(): array;
    public function findById($groupId) : ?Group;
    // public function findClassTotalScore($groupId);
}

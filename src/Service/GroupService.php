<?php

namespace App\Service;

use App\Entity\Group;

interface GroupService
{
    public function findDistinctSchools(): array;
    public function findById($groupId) : ?Group;
    public function findClassTotalScore($groupId);
}

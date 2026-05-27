<?php

namespace App\Service;

interface GroupService
{
    public function findGroupTotalScore(int $groupId): int;
}

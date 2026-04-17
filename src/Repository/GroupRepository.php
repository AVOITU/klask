<?php

namespace App\Repository;

use App\Entity\Group;
//use Doctrine\ORM\QueryBuilder;

interface GroupRepository
{
    //public function findDistinctEstablishments(): array;
    //public function qbBySchool(?string $school): QueryBuilder;
    public function findById(int $idClass): ?Group;

    public function findGroupTotalScore(int $groupId): int;
}

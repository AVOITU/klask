<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\ORM\QueryBuilder;

interface GroupRepository
{
    public function findDistinctSchools(): array;
    public function qbBySchool(?string $school): QueryBuilder;
    public function findById(int $idClass): ?Group;

    public function findClassTotalScore(int $classId): int;
}

<?php

namespace App\Repository;

use App\Entity\Classroom;
use Doctrine\ORM\QueryBuilder;

interface ClassroomRepository
{
    public function findDistinctSchools(): array;
    public function qbBySchool(?string $school): QueryBuilder;
    public function findById(int $idClass): ?Classroom;

    public function findClassTotalScore(int $classId): int;
}

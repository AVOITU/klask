<?php

namespace App\Repository;

use App\Entity\Classroom;

interface ClassroomRepository
{
    public function findDistinctSchools(): array;
    public function findClassesBySchool(string $school): array;
    public function findById(int $idClass): ?Classroom;
}

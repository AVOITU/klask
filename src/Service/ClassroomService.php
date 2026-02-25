<?php

namespace App\Service;

use App\Entity\Classroom;

interface ClassroomService
{
    public function findDistinctSchools(): array;
    public function findClassesBySchool($school): array;
    public function findById($classId) : ?Classroom;
}

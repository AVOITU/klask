<?php

namespace App\Service;

use App\Entity\Classroom;

interface ClassroomService
{
    public function findDistinctSchools(): array;
    public function findById($classId) : ?Classroom;
    public function findClassTotalScore($classId);
}

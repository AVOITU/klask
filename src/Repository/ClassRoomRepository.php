<?php

namespace App\Repository;

use App\Entity\ClassRoom;

interface ClassRoomRepository
{
    public function findDistinctSchools(): array;
    public function findClassesBySchool(string $school): array;
    public function findById(int $idClass): ?ClassRoom;
}

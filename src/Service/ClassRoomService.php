<?php

namespace App\Service;

use App\Entity\ClassRoom;

interface ClassRoomService
{
    public function findAllSchools(): array;
    public function findClassesBySchool($school): array;
    public function findById($classId) : ?ClassRoom;
}

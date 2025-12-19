<?php

namespace Service;

use Model\ClassRoom;

interface ClassRoomService
{
    public function findAllSchools(): array;
    public function findClassesBySchool($school): array;
    public function findById($classId) : ?ClassRoom;
}
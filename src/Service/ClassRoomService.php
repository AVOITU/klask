<?php

namespace Service;

use Model\ClassRoom;

interface ClassRoomService
{
    public function findAll(): array;
    public function findById($classId) : ?ClassRoom;
    public function getAllSchoolsAndClasses(): array;
}
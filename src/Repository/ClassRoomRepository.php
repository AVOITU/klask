<?php

namespace Repository;

use Model\ClassRoom;

interface ClassRoomRepository
{
    public function findAllSchools(): array;
    public function findClassesBySchool(string $school): array;
    public function findById(int $idClass): ?ClassRoom;
    public function getScoresByClass(): array;
}
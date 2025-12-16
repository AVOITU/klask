<?php

namespace Repository;

use Model\ClassRoom;

interface ClassRoomRepository
{
    public function findAll(): array;

    public function findById(int $id_classe): ?ClassRoom;
}
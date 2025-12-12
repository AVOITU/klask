<?php

namespace Repository;

interface ClassRoomRepository
{
    public function findAll(): array;

    public function findById(int $id_classe): ?array;
}
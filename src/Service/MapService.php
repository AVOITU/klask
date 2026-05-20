<?php

namespace App\Service;

interface MapService
{
    /** @return array<int, array{id: int, name: string, color: string, centerX: float, centerY: float, size: float, activities: array}> */
    public function getPreparedSpheres(): array;
}

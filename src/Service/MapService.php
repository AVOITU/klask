<?php

namespace App\Service;

use App\Entity\Activity;
use App\Entity\Sphere;

interface MapService
{
    // @return array<int, array{id: int, name: string, color: string, centerX: float, centerY: float, size: float, activities: array}>
    public function getPreparedSpheres(): array;

    public function savePosition(Sphere|Activity $entity, float $x, float $y, ?float $radius = null): void;
}

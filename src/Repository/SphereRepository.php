<?php

namespace App\Repository;

use App\Entity\Sphere;

interface SphereRepository
{
    /**
     * Récupère toutes les sphères avec leurs activités associées.
     * * @return Sphere[]
     */
    public function findAllWithActivities(): array;
}
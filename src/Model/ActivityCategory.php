<?php

namespace Model;

final class ActivityCategory
{
    /**
     * @param Activity[] $activities
     */
    public function __construct(
        public int $idCategory,
        public string $typeCategory,
        public int $timeMax,
        public int $nbrPoints,
        public int $nbrMaxActivity,
        public Sphere $sphere,          // DECRIT (1,1)
        public array $activities = [],  // REGROUPE (1,N)
    ) {}
}
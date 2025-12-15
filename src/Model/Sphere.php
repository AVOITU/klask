<?php

namespace Model;
final class Sphere
{
    /**
     * @param ActivityCategory[] $activityCategories
     * @param Activity[]         $activities
     */
    public function __construct(
        public int $idSphere,
        public string $nameSphere,
        public string $colorSphere,
        public array $activityCategories = [],
        public array $activities = [],
    ) {}
}


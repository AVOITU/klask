<?php

namespace Model;

final class Activity
{
    /**
     * @param Validation[] $validations
     */
    public function __construct(
        public int $idActivity,
        public string $nameActivity,
        public string $descriptionActivity,
        public string $qrcodeActivity,
        public float $pointX,
        public float $pointY,
        public Sphere $sphere,                    // AFFICHE (1,1)
        public ActivityCategory $activityCategory, // REGROUPE (1,1)
        public Profession $profession,            // APPARTIENT (1,1)
        public array $validations = [],           // ENREGISTRE (1,N)
    ) {}
}
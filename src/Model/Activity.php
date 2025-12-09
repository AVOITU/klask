<?php

namespace Model;

class Activity
{
    public ?int $activityId = null;
    public string $activityName;
    public ?string $activityDescription = null;
    public string $qrCode;
    public int $posX;
    public int $posY;

    public int $categoryId;           // FK
    public int $sphereId;             // FK
    public int $professionId;         // FK

    public ?ActivityCategory $category = null;
    public ?Sphere $sphere = null;
    public ?Profession $profession = null;

    /** @var Validation[] */
    public array $validations = [];
}
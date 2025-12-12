<?php

namespace Model;

class ClassRoom
{
    public ?int $categoryId = null;
    /** 'stand' | 'conference' | 'other' */
    public string $categoryType;
    public int $maxDuration;
    public int $points;
    public ?int $maxStudents = null;

    public int $sphereId;           // FK raw
    public ?Sphere $sphere = null;  // linked object

    /** @var Activity[] */
    public array $activities = [];
}
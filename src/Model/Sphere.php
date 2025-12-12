<?php

namespace Model;
class Sphere
{
    public ?int $sphereId = null;
    public string $sphereName;
    public string $sphereColor;

    /** @var ActivityCategory[] */
    public array $categories = [];

    /** @var Activity[] */
    public array $activities = [];
}

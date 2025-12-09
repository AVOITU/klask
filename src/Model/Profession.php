<?php

namespace Model;

class Profession
{
    public ?int $professionId = null;
    public ?string $professionDescription = null;
    public string $romCode;

    /** @var Activity[] */
    public array $activities = [];
}
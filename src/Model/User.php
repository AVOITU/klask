<?php

namespace Model;

class User
{
    public ?int $userId = null;
    public string $username;
    public string $role;
    public string $authority;

    public int $classId;              // FK raw
    public ?ClassRoom $classRoom = null;  // linked object

    /** @var Validation[] */
    public array $validations = [];
}
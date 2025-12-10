<?php

namespace Model;

class Validation
{
    public ?int $validationId = null;
    public string $validationTime; // Y-m-d H:i:s

    public int $activityId;      // FK
    public int $userId;          // FK

    public ?Activity $activity = null;
    public ?User $user = null;
}
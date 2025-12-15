<?php

namespace Model;
final class Sphere
{
    /** @var ActivityCategory[] */
    private array $activityCategories;

    /** @var Activity[] */
    private array $activities;

    private int $idSphere;
    private string $nameSphere;
    private string $colorSphere;

    /**
     * @param ActivityCategory[] $activityCategories
     * @param Activity[] $activities
     * @param int $idSphere
     * @param string $nameSphere
     * @param string $colorSphere
     */
    public function __construct(array $activityCategories, array $activities, int $idSphere, string $nameSphere, string $colorSphere)
    {
        $this->activityCategories = $activityCategories;
        $this->activities = $activities;
        $this->idSphere = $idSphere;
        $this->nameSphere = $nameSphere;
        $this->colorSphere = $colorSphere;
    }

    public function getActivityCategories(): array
    {
        return $this->activityCategories;
    }

    public function setActivityCategories(array $activityCategories): void
    {
        $this->activityCategories = $activityCategories;
    }

    public function getActivities(): array
    {
        return $this->activities;
    }

    public function setActivities(array $activities): void
    {
        $this->activities = $activities;
    }

    public function getIdSphere(): int
    {
        return $this->idSphere;
    }

    public function setIdSphere(int $idSphere): void
    {
        $this->idSphere = $idSphere;
    }

    public function getNameSphere(): string
    {
        return $this->nameSphere;
    }

    public function setNameSphere(string $nameSphere): void
    {
        $this->nameSphere = $nameSphere;
    }

    public function getColorSphere(): string
    {
        return $this->colorSphere;
    }

    public function setColorSphere(string $colorSphere): void
    {
        $this->colorSphere = $colorSphere;
    }
}


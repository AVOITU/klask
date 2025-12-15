<?php

namespace Model;

final class ActivityCategory
{
    /** @var Activity[] */
    private array $activities;

    private int $idCategory;
    private string $typeCategory;
    private int $timeMax;
    private int $nbrPoints;
    private int $nbrMaxActivity;
    private Sphere $sphere;

    /**
     * @param Activity[] $activities
     * @param int $idCategory
     * @param string $typeCategory
     * @param int $timeMax
     * @param int $nbrPoints
     * @param int $nbrMaxActivity
     * @param Sphere $sphere
     */
    public function __construct(array $activities, int $idCategory, string $typeCategory, int $timeMax, int $nbrPoints, int $nbrMaxActivity, Sphere $sphere)
    {
        $this->activities = $activities;
        $this->idCategory = $idCategory;
        $this->typeCategory = $typeCategory;
        $this->timeMax = $timeMax;
        $this->nbrPoints = $nbrPoints;
        $this->nbrMaxActivity = $nbrMaxActivity;
        $this->sphere = $sphere;
    }

    public function getActivities(): array
    {
        return $this->activities;
    }

    public function setActivities(array $activities): void
    {
        $this->activities = $activities;
    }

    public function getIdCategory(): int
    {
        return $this->idCategory;
    }

    public function setIdCategory(int $idCategory): void
    {
        $this->idCategory = $idCategory;
    }

    public function getTypeCategory(): string
    {
        return $this->typeCategory;
    }

    public function setTypeCategory(string $typeCategory): void
    {
        $this->typeCategory = $typeCategory;
    }

    public function getTimeMax(): int
    {
        return $this->timeMax;
    }

    public function setTimeMax(int $timeMax): void
    {
        $this->timeMax = $timeMax;
    }

    public function getNbrPoints(): int
    {
        return $this->nbrPoints;
    }

    public function setNbrPoints(int $nbrPoints): void
    {
        $this->nbrPoints = $nbrPoints;
    }

    public function getNbrMaxActivity(): int
    {
        return $this->nbrMaxActivity;
    }

    public function setNbrMaxActivity(int $nbrMaxActivity): void
    {
        $this->nbrMaxActivity = $nbrMaxActivity;
    }

    public function getSphere(): Sphere
    {
        return $this->sphere;
    }

    public function setSphere(Sphere $sphere): void
    {
        $this->sphere = $sphere;
    }
}
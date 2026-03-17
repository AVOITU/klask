<?php

namespace App\Entity;

use App\Repository\Impl\SphereRepositoryImpl;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SphereRepositoryImpl::class)]
final class Sphere
{
    /** @var ActivityCategory[] */
    #[ORM\Column]
    private array $activityCategories;

    /** @var Activity[] */
    #[ORM\Column]
    private array $activities;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $idSphere;
    #[ORM\Column]
    private string $nameSphere;
    #[ORM\Column]
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


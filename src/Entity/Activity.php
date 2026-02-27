<?php

namespace App\Entity;

use App\Repository\Impl\ActivityRepositoryImpl;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepositoryImpl::class)]
final class Activity
{
    /** @var Scan[] */
    #[ORM\Column]
    private array $validations;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $idActivity;
    #[ORM\Column]
    private string $nameActivity;
    #[ORM\Column]
    private string $descriptionActivity;
    #[ORM\Column]
    private string $qrcodeActivity;
    #[ORM\Column]
    private float $pointX;
    #[ORM\Column]
    private float $pointY;
    #[ORM\Column]
    private Sphere $sphere;
    #[ORM\Column]
    private ActivityCategory $activityCategory;
    #[ORM\Column]
    private Profession $profession;

    /**
     * @param Scan[] $validations
     * @param int $idActivity
     * @param string $nameActivity
     * @param string $descriptionActivity
     * @param string $qrcodeActivity
     * @param float $pointX
     * @param float $pointY
     * @param Sphere $sphere
     * @param ActivityCategory $activityCategory
     * @param Profession $profession
     */
    public function __construct(array $validations, int $idActivity, string $nameActivity, string $descriptionActivity, string $qrcodeActivity, float $pointX, float $pointY, Sphere $sphere, ActivityCategory $activityCategory, Profession $profession)
    {
        $this->validations = $validations;
        $this->idActivity = $idActivity;
        $this->nameActivity = $nameActivity;
        $this->descriptionActivity = $descriptionActivity;
        $this->qrcodeActivity = $qrcodeActivity;
        $this->pointX = $pointX;
        $this->pointY = $pointY;
        $this->sphere = $sphere;
        $this->activityCategory = $activityCategory;
        $this->profession = $profession;
    }

    public function getValidations(): array
    {
        return $this->validations;
    }

    public function setValidations(array $validations): void
    {
        $this->validations = $validations;
    }

    public function getIdActivity(): int
    {
        return $this->idActivity;
    }

    public function setIdActivity(int $idActivity): void
    {
        $this->idActivity = $idActivity;
    }

    public function getNameActivity(): string
    {
        return $this->nameActivity;
    }

    public function setNameActivity(string $nameActivity): void
    {
        $this->nameActivity = $nameActivity;
    }

    public function getDescriptionActivity(): string
    {
        return $this->descriptionActivity;
    }

    public function setDescriptionActivity(string $descriptionActivity): void
    {
        $this->descriptionActivity = $descriptionActivity;
    }

    public function getQrcodeActivity(): string
    {
        return $this->qrcodeActivity;
    }

    public function setQrcodeActivity(string $qrcodeActivity): void
    {
        $this->qrcodeActivity = $qrcodeActivity;
    }

    public function getPointX(): float
    {
        return $this->pointX;
    }

    public function setPointX(float $pointX): void
    {
        $this->pointX = $pointX;
    }

    public function getPointY(): float
    {
        return $this->pointY;
    }

    public function setPointY(float $pointY): void
    {
        $this->pointY = $pointY;
    }

    public function getSphere(): Sphere
    {
        return $this->sphere;
    }

    public function setSphere(Sphere $sphere): void
    {
        $this->sphere = $sphere;
    }

    public function getActivityCategory(): ActivityCategory
    {
        return $this->activityCategory;
    }

    public function setActivityCategory(ActivityCategory $activityCategory): void
    {
        $this->activityCategory = $activityCategory;
    }

    public function getProfession(): Profession
    {
        return $this->profession;
    }

    public function setProfession(Profession $profession): void
    {
        $this->profession = $profession;
    }
}

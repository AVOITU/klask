<?php

namespace App\Entity;

use App\Repository\Impl\SphereRepositoryImpl;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SphereRepositoryImpl::class)]
class Sphere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;
    #[ORM\Column(length: 100, unique: true)]
    private string $nameSphere;
    #[ORM\Column(length: 50, unique: true)]
    private string $colorSphere;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionSphere = null;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'sphere')]
    private Collection $activities;

    #[ORM\ManyToOne(inversedBy: 'spheres')]
    private ?ActivityCategory $category = null;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getDescriptionSphere(): ?string
    {
        return $this->descriptionSphere;
    }

    public function setDescriptionSphere(?string $descriptionSphere): static
    {
        $this->descriptionSphere = $descriptionSphere;

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivities(Activity $activities): static
    {
        if (!$this->activities->contains($activities)) {
            $this->activities->add($activities);
            $activities->setSphere($this);
        }

        return $this;
    }

    public function removeActivities(Activity $activities): static
    {
        if ($this->activities->removeElement($activities)) {
            // set the owning side to null (unless already changed)
            if ($activities->getSphere() === $this) {
                $activities->setSphere(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?ActivityCategory
    {
        return $this->category;
    }

    public function setCategory(?ActivityCategory $category): static
    {
        $this->category = $category;

        return $this;
    }
}


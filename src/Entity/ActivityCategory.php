<?php

namespace App\Entity;

use App\Repository\Impl\ActivityCategoryRepositoryImpl;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityCategoryRepositoryImpl::class)]
final class ActivityCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $idCategory;
    #[ORM\Column(length: 255)]
    private string $typeCategory;
    #[ORM\Column]
    private int $nbrPoints;
    #[ORM\Column]
    private int $nbrMaxActivity;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $beginningHourCategory = null;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'category', orphanRemoval: true)]
    private Collection $activities;

    /**
     * @var Collection<int, Sphere>
     */
    #[ORM\OneToMany(targetEntity: Sphere::class, mappedBy: 'category')]
    private Collection $spheres;


    /**
     * @param int $idCategory
     * @param string $typeCategory
     * @param int $nbrPoints
     * @param int $nbrMaxActivity
     */
    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->spheres = new ArrayCollection();
    }



    public function getTypeCategory(): string
    {
        return $this->typeCategory;
    }

    public function setTypeCategory(string $typeCategory): void
    {
        $this->typeCategory = $typeCategory;
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

    public function getBeginningHourCategory(): ?\DateTimeImmutable
    {
        return $this->beginningHourCategory;
    }

    public function setBeginningHourCategory(?\DateTimeImmutable $beginningHourCategory): static
    {
        $this->beginningHourCategory = $beginningHourCategory;

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
            $activities->setCategory($this);
        }

        return $this;
    }

    public function removeActivities(Activity $activities): static
    {
        if ($this->activities->removeElement($activities)) {
            // set the owning side to null (unless already changed)
            if ($activities->getCategory() === $this) {
                $activities->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sphere>
     */
    public function getSpheres(): Collection
    {
        return $this->spheres;
    }

    public function addSphere(Sphere $sphere): static
    {
        if (!$this->spheres->contains($sphere)) {
            $this->spheres->add($sphere);
            $sphere->setCategory($this);
        }

        return $this;
    }

    public function removeSphere(Sphere $sphere): static
    {
        if ($this->spheres->removeElement($sphere)) {
            // set the owning side to null (unless already changed)
            if ($sphere->getCategory() === $this) {
                $sphere->setCategory(null);
            }
        }

        return $this;
    }

}

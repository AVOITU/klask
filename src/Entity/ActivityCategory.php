<?php

namespace App\Entity;

use App\Repository\ActivityCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityCategoryRepository::class)]
class ActivityCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    private string $type;

    #[ORM\Column(options: ['unsigned' => true])]
    private int $nbrPoints;

    #[ORM\Column]
    private int $nbrMaxActivity;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $beginningHourCategory = null;

    #[ORM\Column(type: 'text', length: 500, nullable: true)]
    private ?string $restrictions = null;

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

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->spheres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getNbrPoints(): int
    {
        return $this->nbrPoints;
    }

    public function setNbrPoints(int $nbrPoints): static
    {
        $this->nbrPoints = $nbrPoints;

        return $this;
    }

    public function getNbrMaxActivity(): int
    {
        return $this->nbrMaxActivity;
    }

    public function setNbrMaxActivity(int $nbrMaxActivity): static
    {
        $this->nbrMaxActivity = $nbrMaxActivity;

        return $this;
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

    public function getRestrictions(): ?string
    {
        return $this->restrictions;
    }

    public function setRestrictions(?string $restrictions): static
    {
        $this->restrictions = $restrictions;

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setCategory($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity) && $activity->getCategory() === $this) {
            $activity->setCategory(null);
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
        if ($this->spheres->removeElement($sphere) && $sphere->getCategory() === $this) {
            $sphere->setCategory(null);
        }

        return $this;
    }

    public function __toString(): string
    {

        return $this->type;
    }
}

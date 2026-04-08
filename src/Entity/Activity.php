<?php

namespace App\Entity;

use App\Repository\Impl\ActivityRepositoryImpl;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepositoryImpl::class)]
final class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $nameActivity = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $descriptionActivity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $qrcodeActivity = null;

    #[ORM\Column(nullable: true)]
    private ?float $pointXActivity = null;

    #[ORM\Column(nullable: true)]
    private ?float $pointYActivity = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Sphere $sphere = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ActivityCategory $category = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Profession $profession = null;

    /**
     * @var Collection<int, Scan>
     */
    #[ORM\OneToMany(targetEntity: Scan::class, mappedBy: 'activity')]
    private Collection $scans;

    public function __construct()
    {
        $this->scans = new ArrayCollection();
    }

    public function getNameActivity(): ?string
    {
        return $this->nameActivity;
    }

    public function setNameActivity(string $nameActivity): static
    {
        $this->nameActivity = $nameActivity;

        return $this;
    }

    public function getDescriptionActivity(): ?string
    {
        return $this->descriptionActivity;
    }

    public function setDescriptionActivity(?string $descriptionActivity): static
    {
        $this->descriptionActivity = $descriptionActivity;

        return $this;
    }

    public function getQrcodeActivity(): ?string
    {
        return $this->qrcodeActivity;
    }

    public function setQrcodeActivity(?string $qrcodeActivity): static
    {
        $this->qrcodeActivity = $qrcodeActivity;

        return $this;
    }

    public function getPointXActivity(): ?float
    {
        return $this->pointXActivity;
    }

    public function setPointXActivity(?float $pointXActivity): static
    {
        $this->pointXActivity = $pointXActivity;

        return $this;
    }

    public function getPointYActivity(): ?float
    {
        return $this->pointYActivity;
    }

    public function setPointYActivity(?float $pointYActivity): static
    {
        $this->pointYActivity = $pointYActivity;

        return $this;
    }

    public function getSphere(): ?Sphere
    {
        return $this->sphere;
    }

    public function setSphere(?Sphere $sphere): static
    {
        $this->sphere = $sphere;

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

    public function getProfession(): ?Profession
    {
        return $this->profession;
    }

    public function setProfession(?Profession $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * @return Collection<int, Scan>
     */
    public function getScans(): Collection
    {
        return $this->scans;
    }

    public function addScan(Scan $scan): static
    {
        if (!$this->scans->contains($scan)) {
            $this->scans->add($scan);
            $scan->setActivity($this);
        }

        return $this;
    }

    public function removeScan(Scan $scan): static
    {
        if ($this->scans->removeElement($scan)) {
            // set the owning side to null (unless already changed)
            if ($scan->getActivity() === $this) {
                $scan->setActivity(null);
            }
        }

        return $this;
    }
}

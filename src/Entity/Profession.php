<?php

namespace App\Entity;

use App\Repository\Impl\ProfessionRepositoryImpl;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfessionRepositoryImpl::class)]
final class Profession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $descriptionProfession = null;
    #[ORM\Column(length: 5)]
    private string $codeRom;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'profession')]
    private Collection $activities;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $narratorProfession = null;

    /**
     * @param int $idProfession
     * @param string $descriptionProfession
     * @param string $codeRom
     */
    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }



    public function getDescriptionProfession(): string
    {
        return $this->descriptionProfession;
    }

    public function setDescriptionProfession(string $descriptionProfession): void
    {
        $this->descriptionProfession = $descriptionProfession;
    }

    public function getCodeRom(): string
    {
        return $this->codeRom;
    }
    public function getNarratorProfession(): ?string
    {
        return $this->narratorProfession;
    }

    public function setNarratorProfession(?string $narratorProfession): static
    {
        $this->narratorProfession = $narratorProfession;

        return $this;
    }

    public function setCodeRom(string $codeRom): void
    {
        $this->codeRom = $codeRom;
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
            $activity->setProfession($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getProfession() === $this) {
                $activity->setProfession(null);
            }
        }

        return $this;
    }
}

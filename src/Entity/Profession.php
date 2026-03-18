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
    private int $idProfession;
    #[ORM\Column]
    private string $descriptionProfession;
    #[ORM\Column]
    private string $codeRom;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'profession')]
    private Collection $activities;

    /**
     * @param int $idProfession
     * @param string $descriptionProfession
     * @param string $codeRom
     */
    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    public function getIdProfession(): int
    {
        return $this->idProfession;
    }

    public function setIdProfession(int $idProfession): void
    {
        $this->idProfession = $idProfession;
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

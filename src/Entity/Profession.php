<?php

namespace App\Entity;

use App\Repository\ProfessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfessionRepository::class)]
class Profession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $description = null;

    // code ROME officiel France Travail
    #[ORM\Column(length: 5, unique: true, nullable: true)]
    private ?string $codeRome = null;

    // prénom du professionnel conteur. Pop-up de quête affichée lors du scan si non null
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $narrator = null;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'profession')]
    private Collection $activities;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCodeRome(): ?string
    {
        return $this->codeRome;
    }

    public function setCodeRome(?string $codeRome): static
    {
        $this->codeRome = $codeRome;

        return $this;
    }

    public function getNarrator(): ?string
    {
        return $this->narrator;
    }

    public function setNarrator(?string $narrator): static
    {
        $this->narrator = $narrator;

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    // addActivity/removeActivity inutilisés (relation Profession↔Activity non active pour le moment!)
    // public function addActivity(Activity $activity): static
    // {
    //     if (!$this->activities->contains($activity)) {
    //         $this->activities->add($activity);
    //         $activity->setProfession($this);
    //     }
    //     return $this;
    // }

    // public function removeActivity(Activity $activity): static
    // {
    //     if ($this->activities->removeElement($activity) && $activity->getProfession() === $this) {
    //         $activity->setProfession(null);
    //     }
    //     return $this;
    // }
}

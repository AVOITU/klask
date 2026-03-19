<?php

namespace App\Entity;

use App\Repository\EstablishmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstablishmentRepository::class)]
class Establishment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $idEstablishment;

    #[ORM\Column(length: 100)]
    private ?string $name_establishment = null;

    /**
     * @var Collection<int, Classroom>
     */
    #[ORM\OneToMany(targetEntity: Classroom::class, mappedBy: 'establishment', orphanRemoval: true)]
    private Collection $classrooms;

    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
    }

    public function getNameEstablishment(): ?string
    {
        return $this->name_establishment;
    }

    public function setNameEstablishment(string $name_establishment): static
    {
        $this->name_establishment = $name_establishment;

        return $this;
    }

    /**
     * @return Collection<int, Classroom>
     */
    public function getClassrooms(): Collection
    {
        return $this->classrooms;
    }

    public function addClassroom(Classroom $classroom): static
    {
        if (!$this->classrooms->contains($classroom)) {
            $this->classrooms->add($classroom);
            $classroom->setEstablishment($this);
        }

        return $this;
    }

    public function removeClassroom(Classroom $classroom): static
    {
        if ($this->classrooms->removeElement($classroom)) {
            // set the owning side to null (unless already changed)
            if ($classroom->getEstablishment() === $this) {
                $classroom->setEstablishment(null);
            }
        }

        return $this;
    }

}

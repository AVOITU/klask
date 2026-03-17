<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idEvent = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endHour = null;

    /**
     * @var Collection<int, Classroom>
     */
    #[ORM\OneToMany(targetEntity: Classroom::class, mappedBy: 'event', orphanRemoval: true)]
    private Collection $classrooms;

    /**
     * @var Collection<int, Classroom>
     */
    #[ORM\OneToMany(targetEntity: Classroom::class, mappedBy: 'idEvent', orphanRemoval: true)]
    private Collection $classroomss;

    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
        $this->classroomss = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdEvent(): ?int
    {
        return $this->idEvent;
    }

    public function setIdEvent(int $idEvent): static
    {
        $this->idEvent = $idEvent;

        return $this;
    }

    public function getEndHour(): ?\DateTimeImmutable
    {
        return $this->endHour;
    }

    public function setEndHour(\DateTimeImmutable $endHour): static
    {
        $this->endHour = $endHour;

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
            $classroom->setEvent($this);
        }

        return $this;
    }

    public function removeClassroom(Classroom $classroom): static
    {
        if ($this->classrooms->removeElement($classroom)) {
            // set the owning side to null (unless already changed)
            if ($classroom->getEvent() === $this) {
                $classroom->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Classroom>
     */
    public function getClassroomss(): Collection
    {
        return $this->classroomss;
    }

    public function addClassroomss(Classroom $classroomss): static
    {
        if (!$this->classroomss->contains($classroomss)) {
            $this->classroomss->add($classroomss);
            $classroomss->setIdEvent($this);
        }

        return $this;
    }

    public function removeClassroomss(Classroom $classroomss): static
    {
        if ($this->classroomss->removeElement($classroomss)) {
            // set the owning side to null (unless already changed)
            if ($classroomss->getIdEvent() === $this) {
                $classroomss->setIdEvent(null);
            }
        }

        return $this;
    }
}

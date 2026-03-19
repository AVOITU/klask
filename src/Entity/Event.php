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
    private int $idEvent;

    #[ORM\Column(length: 50)]
    private ?string $nameEvent = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $beginningHourEvent = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endHourEvent = null;

    /**
     * @var Collection<int, Classroom>
     */
    #[ORM\OneToMany(targetEntity: Classroom::class, mappedBy: 'event')]
    private Collection $classrooms;

    public function __construct()
    {
        $this->classrooms = new ArrayCollection();
    }

    public function getNameEvent(): ?string
    {
        return $this->nameEvent;
    }

    public function setNameEvent(string $nameEvent): static
    {
        $this->nameEvent = $nameEvent;

        return $this;
    }

    public function getBeginningHourEvent(): ?\DateTimeImmutable
    {
        return $this->beginningHourEvent;
    }

    public function setBeginningHourEvent(?\DateTimeImmutable $beginningHourEvent): static
    {
        $this->beginningHourEvent = $beginningHourEvent;

        return $this;
    }

    public function getEndHourEvent(): ?\DateTimeImmutable
    {
        return $this->endHourEvent;
    }

    public function setEndHourEvent(?\DateTimeImmutable $endHourEvent): static
    {
        $this->endHourEvent = $endHourEvent;

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


}

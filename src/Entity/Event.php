<?php

namespace App\Entity;

use App\Repository\Impl\EventRepositoryImpl;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepositoryImpl::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 50)]
    private ?string $nameEvent = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $beginningHourEvent = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endHourEvent = null;

    /**
     * @var Collection<int, Group>
     */
    #[ORM\OneToMany(targetEntity: Group::class, mappedBy: 'event')]
    private Collection $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
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
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): static
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->setEvent($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): static
    {
        if ($this->groups->removeElement($group)) {
            // set the owning side to null (unless already changed)
            if ($group->getEvent() === $this) {
                $group->setEvent(null);
            }
        }

        return $this;
    }


}

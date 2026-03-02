<?php

namespace App\Entity;

use App\Repository\Impl\ClassroomRepositoryImpl;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassroomRepositoryImpl::class)]
class Classroom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $idClass;
    #[ORM\Column]
    private string $school;
    #[ORM\Column]
    private string $className;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'classroom')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getIdClass(): int
    {
        return $this->idClass;
    }

    public function getSchool(): string
    {
        return $this->school;
    }

    public function setSchool(string $school): void
    {
        $this->school = $school;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUsers(User $users): static
    {
        if (!$this->users->contains($users)) {
            $this->users->add($users);
            $users->setClassroom($this);
        }

        return $this;
    }

    public function removeUsers(User $users): static
    {
        if ($this->users->removeElement($users)) {
            // set the owning side to null (unless already changed)
            if ($users->getClassroom() === $this) {
                $users->setClassroom(null);
            }
        }
        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\Impl\UserRepositoryImpl;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepositoryImpl::class)]
final class User
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private int $idUser;
    #[ORM\Column]
    private string $pseudoUser;
    #[ORM\Column]
    private Classroom $classRoom;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Classroom $classroom = null;

    /**
     * @var Collection<int, Scan>
     */
    #[ORM\OneToMany(targetEntity: Scan::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $validations;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Authority $authority = null;

    /**
     * @param int $idUser
     * @param string $pseudoUser
     * @param Classroom $classRoom
     */
    public function __construct(int   $idUser, string $pseudoUser,
                                Classroom $classRoom)
    {
        $this->idUser = $idUser;
        $this->pseudoUser = $pseudoUser;
        $this->classRoom = $classRoom;
        $this->validations = new ArrayCollection();
    }
    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): void
    {
        $this->idUser = $idUser;
    }

    public function getPseudoUser(): string
    {
        return $this->pseudoUser;
    }

    public function setPseudoUser(string $pseudoUser): void
    {
        $this->pseudoUser = $pseudoUser;
    }

    public function getClassRoom(): Classroom
    {
        return $this->classRoom;
    }

    public function setClassRoom(Classroom $classRoom): void
    {
        $this->classRoom = $classRoom;
    }

    /**
     * @return Collection<int, Scan>
     */
    public function getValidations(): Collection
    {
        return $this->validations;
    }

    public function addValidation(Scan $validation): static
    {
        if (!$this->validations->contains($validation)) {
            $this->validations->add($validation);
            $validation->setUser($this);
        }

        return $this;
    }

    public function removeValidation(Scan $validation): static
    {
        if ($this->validations->removeElement($validation)) {
            // set the owning side to null (unless already changed)
            if ($validation->getUser() === $this) {
                $validation->setUser(null);
            }
        }
        return $this;
    }

    public function getAuthority(): ?Authority
    {
        return $this->authority;
    }

    public function setAuthority(?Authority $authority): static
    {
        $this->authority = $authority;
        return $this;
    }
}

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

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'id_class', referencedColumnName: 'id_class', nullable: false)]
    private ?Classroom $classroom = null;

    /**
     * @var Collection<int, Scan>
     */
    #[ORM\OneToMany(targetEntity: Scan::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $validations;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'id_authority', referencedColumnName: 'id_authority', nullable: false)]
    private ?Authority $authority = null;


    public function __construct()
    {
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

    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): self
    {
        $this->classroom = $classroom;
        return $this;
    }

    /**
     * @return Collection<int, Scan>
     */
    public function getValidations(): Collection
    {
        return $this->validations;
    }

    public function addValidation(Scan $validation): self
    {
        if (!$this->validations->contains($validation)) {
            $this->validations->add($validation);
            $validation->setUser($this);
        }

        return $this;
    }

    public function removeValidation(Scan $validation): self
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

    public function setAuthority(?Authority $authority): self
    {
        $this->authority = $authority;
        return $this;
    }
}

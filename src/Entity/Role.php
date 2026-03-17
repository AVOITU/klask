<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idRole = null;

    #[ORM\Column(length: 255)]
    private ?string $nameRole = null;

    /**
     * @var Collection<int, Authority>
     */
    #[ORM\ManyToMany(targetEntity: Authority::class, inversedBy: 'roles')]
    private Collection $authorities;

    public function __construct()
    {
        $this->authorities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdRole(): ?int
    {
        return $this->idRole;
    }

    public function setIdRole(int $idRole): static
    {
        $this->idRole = $idRole;

        return $this;
    }

    public function getNameRole(): ?string
    {
        return $this->nameRole;
    }

    public function setNameRole(string $nameRole): static
    {
        $this->nameRole = $nameRole;

        return $this;
    }

    /**
     * @return Collection<int, Authority>
     */
    public function getAuthorities(): Collection
    {
        return $this->authorities;
    }

    public function addAutority(Authority $autority): static
    {
        if (!$this->authorities->contains($autority)) {
            $this->authorities->add($autority);
        }

        return $this;
    }

    public function removeAutority(Authority $autority): static
    {
        $this->authorities->removeElement($autority);

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\Impl\RoleRepositoryImpl;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepositoryImpl::class)]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 50)]
    private ?string $nameRole = null;

    /**
     * @var Collection<int, AuthorityRole>
     */
    #[ORM\OneToMany(targetEntity: AuthorityRole::class, mappedBy: 'role')]
    private Collection $authorityRoles;

    public function __construct()
    {
        $this->authorityRoles = new ArrayCollection();
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
     * @return Collection<int, AuthorityRole>
     */
    public function getAuthorityRoles(): Collection
    {
        return $this->authorityRoles;
    }

    public function addAuthorityRole(AuthorityRole $authorityRole): static
    {
        if (!$this->authorityRoles->contains($authorityRole)) {
            $this->authorityRoles->add($authorityRole);
            $authorityRole->setRole($this);
        }

        return $this;
    }

    public function removeAuthorityRole(AuthorityRole $authorityRole): static
    {
        if ($this->authorityRoles->removeElement($authorityRole)) {
            // set the owning side to null (unless already changed)
            if ($authorityRole->getRole() === $this) {
                $authorityRole->setRole(null);
            }
        }

        return $this;
    }
}

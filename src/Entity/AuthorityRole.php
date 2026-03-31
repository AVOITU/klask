<?php

namespace App\Entity;

use App\Repository\Impl\AuthorityRoleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorityRoleRepository::class)]
class AuthorityRole
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'authorityRoles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Authority $authority = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'authorityRoles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;


    public function getAuthority(): ?Authority
    {
        return $this->authority;
    }

    public function setAuthority(?Authority $authority): static
    {
        $this->authority = $authority;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): static
    {
        $this->role = $role;

        return $this;
    }
}

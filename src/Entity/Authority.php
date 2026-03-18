<?php

namespace App\Entity;

use App\Repository\Impl\AuthorityRepositoryImpl;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorityRepositoryImpl::class)]
final class Authority
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $idAuthority;
    #[ORM\Column]
    private string $authorityUser;

    /**
     * @param int $idAuthority
     * @param string $authorityUser
     */
    public function __construct()
    {
    }

    public function getIdAuthority(): int
    {
        return $this->idAuthority;
    }

    public function setIdAuthority(int $idAuthority): void
    {
        $this->idAuthority = $idAuthority;
    }

    public function getAuthorityUser(): string
    {
        return $this->authorityUser;
    }

    public function setAuthorityUser(string $authorityUser): void
    {
        $this->authorityUser = $authorityUser;
    }
}

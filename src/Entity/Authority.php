<?php

namespace App\Entity;

use App\Repository\Impl\AuthorityRepositoryImpl;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorityRepositoryImpl::class)]
final class Authority
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $idAuthority;
    #[ORM\Column]
    private string $roleUser;
    #[ORM\Column]
    private string $authorityUser;
    #[ORM\Column]
    private array $user;

    /**
     * @param User[] $users
     * @param int $idAuthority
     * @param string $roleUser
     * @param string $authorityUser
     */
    public function __construct(array $users, int $idAuthority, string $roleUser, string $authorityUser)
    {
        $this->idAuthority = $idAuthority;
        $this->roleUser = $roleUser;
        $this->authorityUser = $authorityUser;
        $this->user = $users;
    }

    public function getIdAuthority(): int
    {
        return $this->idAuthority;
    }

    public function setIdAuthority(int $idAuthority): void
    {
        $this->idAuthority = $idAuthority;
    }

    public function getRoleUser(): string
    {
        return $this->roleUser;
    }

    public function setRoleUser(string $roleUser): void
    {
        $this->roleUser = $roleUser;
    }

    public function getUser(): array
    {
        return $this->user;
    }

    public function setUser(array $user): void
    {
        $this->user = $user;
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

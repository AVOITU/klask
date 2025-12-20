<?php

namespace Model;


final class Authority
{
    private int $idAuthority;
    private string $roleUser;
    private string $authorityUser;
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
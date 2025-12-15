<?php

namespace Model;


final class Authority
{
    private int $idAuthority;
    private string $roleUser;
    private string $authorityUser;
    private User $user;

    /**
     * @param int $idAuthority
     * @param string $roleUser
     * @param string $authorityUser
     * @param User $user
     */
    public function __construct(int $idAuthority, string $roleUser, string $authorityUser, User $user)
    {
        $this->idAuthority = $idAuthority;
        $this->roleUser = $roleUser;
        $this->authorityUser = $authorityUser;
        $this->user = $user;
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

    public function getAuthorityUser(): string
    {
        return $this->authorityUser;
    }

    public function setAuthorityUser(string $authorityUser): void
    {
        $this->authorityUser = $authorityUser;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
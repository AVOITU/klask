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
    private string $roleUser;
    #[ORM\Column]
    private string $authorityUser;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'authority', orphanRemoval: true)]
    private Collection $users;

    /**
     * @var Collection<int, Role>
     */
    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: 'authorities')]
    private Collection $roles;

    /**
     * @param int $idAuthority
     * @param string $roleUser
     * @param string $authorityUser
     */
    public function __construct(int $idAuthority, string $roleUser, string $authorityUser)
    {
        $this->idAuthority = $idAuthority;
        $this->roleUser = $roleUser;
        $this->authorityUser = $authorityUser;
        $this->users = new ArrayCollection();
        $this->roles = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setAuthority($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAuthority() === $this) {
                $user->setAuthority(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): static
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
            $role->addAutority($this);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        if ($this->roles->removeElement($role)) {
            $role->removeAutority($this);
        }

        return $this;
    }
}

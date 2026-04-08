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
    private int $id;
    #[ORM\Column (length: 191, unique: true)]
    private string $pseudoUser;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Authority $authority = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $group = null;

    /**
     * @var Collection<int, Scan>
     */
    #[ORM\OneToMany(targetEntity: Scan::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $scans;

    public function __construct()
    {
        $this->scans = new ArrayCollection();
    }


    public function getPseudoUser(): string
    {
        return $this->pseudoUser;
    }

    public function setPseudoUser(string $pseudoUser): void
    {
        $this->pseudoUser = $pseudoUser;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

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

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): static
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return Collection<int, Scan>
     */
    public function getScans(): Collection
    {
        return $this->scans;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function addScan(Scan $scan): static
    {
        if (!$this->scans->contains($scan)) {
            $this->scans->add($scan);
            $scan->setUser($this);
        }

        return $this;
    }

    public function removeScan(Scan $scan): static
    {
        if ($this->scans->removeElement($scan)) {
            // set the owning side to null (unless already changed)
            if ($scan->getUser() === $this) {
                $scan->setUser(null);
            }
        }

        return $this;
    }
}

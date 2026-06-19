<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 180, unique: true, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    // Null = non bloqué
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $blockedUntil = null;

    // horodatage du dernier poke envoyé par l'accompagnateur. Null = pas de poke en attente
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $pokedAt = null;

    //compteur de scans invalides consécutifs. Remis à 0 après tout scan valide
    #[ORM\Column(options: ['default' => 0])]
    private int $invalidScanCount = 0;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $groupCode = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Authority $authority = null;

    // Null pour les admins
    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
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

    // id unique Security : email pour staff, pseudo pour les élèves (pas d'email)
    public function getUserIdentifier(): string
    {
        return $this->email ?? $this->pseudo ?? '';
    }

    // La BDD stocke STUDENT/ADMIN — Symfony exige le préfixe ROLE_
    public function getRoles(): array
    {
        $roles = [];

        foreach ($this->authority?->getAuthorityRoles() ?? [] as $authorityRole) {
            $name = $authorityRole->getRole()?->getNameRole();
            if ($name !== null) {
                $roles[] = 'ROLE_' . $name;
            }
        }

        return $roles ?: ['ROLE_STUDENT'];
    }

    //efface les données sensibles en clair dans la mémoire
    public function eraseCredentials(): void
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
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

    public function getBlockedUntil(): ?\DateTimeImmutable
    {
        return $this->blockedUntil;
    }

    public function setBlockedUntil(?\DateTimeImmutable $blockedUntil): static
    {
        $this->blockedUntil = $blockedUntil;

        return $this;
    }

    public function getInvalidScanCount(): int
    {
        return $this->invalidScanCount;
    }

    public function setInvalidScanCount(int $invalidScanCount): static
    {
        $this->invalidScanCount = $invalidScanCount;

        return $this;
    }

    public function getPokedAt(): ?\DateTimeImmutable
    {
        return $this->pokedAt;
    }

    public function setPokedAt(?\DateTimeImmutable $pokedAt): static
    {
        $this->pokedAt = $pokedAt;

        return $this;
    }

    public function getGroupCode(): ?string
    {
        return $this->groupCode;
    }

    public function setGroupCode(?string $groupCode): static
    {
        $this->groupCode = $groupCode;

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
        if ($this->scans->removeElement($scan) && $scan->getUser() === $this) {
            $scan->setUser(null);
        }

        return $this;
    }

    private ?string $plainPassword = null;

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }
}

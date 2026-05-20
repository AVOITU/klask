<?php

namespace App\Entity;

use App\Repository\ScanRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScanRepository::class)]
class Scan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    //Immuable "normalement" (c'est à dire non modifiable) - plus le cas si on démarre avec un timestamp à 0
    #[ORM\Column]
    private DateTimeImmutable $hourValidation;

    #[ORM\ManyToOne(inversedBy: 'scans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activity $activity = null;

    #[ORM\ManyToOne(inversedBy: 'scans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct(DateTimeImmutable $hourValidation)
    {
        $this->hourValidation = $hourValidation;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHourValidation(): DateTimeImmutable
    {
        return $this->hourValidation;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}

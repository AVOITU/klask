<?php

namespace App\Entity;

use App\Repository\Impl\ScanRepositoryImpl;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScanRepositoryImpl::class)]
final class Scan
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private int $idScan;
    

    #[ORM\Column]
    private ?\DateTimeImmutable $hourValidation = null;

    #[ORM\ManyToOne(inversedBy: 'scans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activity $activity = null;

    #[ORM\ManyToOne(inversedBy: 'scans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;




    /**
     * @param int $idScan
     */
    public function __construct()
    {    }

    public function getHourValidation(): ?\DateTimeImmutable
    {
        return $this->hourValidation;
    }

    public function setHourValidation(\DateTimeImmutable $hourValidation): static
    {
        $this->hourValidation = $hourValidation;

        return $this;
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

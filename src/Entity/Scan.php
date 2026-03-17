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
    private string $hourScan;
    #[ORM\Column]
    private Activity $activity;

    #[ORM\ManyToOne(inversedBy: 'scans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getIdscan(): int
    {
        return $this->idScan;
    }

    public function setIdScan(int $idScan): void
    {
        $this->idScan = $idScan;
    }

    public function getHourScan(): string
    {
        return $this->hourScan;
    }

    public function setHourScan(string $hourScan): void
    {
        $this->hourScan = $hourScan;
    }

    public function getActivity(): Activity
    {
        return $this->activity;
    }

    public function setActivity(Activity $activity): void
    {
        $this->activity = $activity;
    }

    /**
     * @param int $idScan
     * @param string $hourScan
     * @param Activity $activity
     */
    public function __construct(int $idScan, string $hourScan, Activity $activity)
    {
        $this->idScan = $idScan;
        $this->hourScan = $hourScan;
        $this->activity = $activity;
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

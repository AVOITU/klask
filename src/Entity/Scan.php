<?php

namespace App\Entity;

use App\Repository\Impl\ValidationRepositoryImpl;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValidationRepositoryImpl::class)]
final class Scan
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    private int $idValidation;
    #[ORM\Column]
    private string $hourValidation;
    #[ORM\Column]
    private Activity $activity;

    #[ORM\ManyToOne(inversedBy: 'validations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getIdValidation(): int
    {
        return $this->idValidation;
    }

    public function setIdValidation(int $idValidation): void
    {
        $this->idValidation = $idValidation;
    }

    public function getHourValidation(): string
    {
        return $this->hourValidation;
    }

    public function setHourValidation(string $hourValidation): void
    {
        $this->hourValidation = $hourValidation;
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
     * @param int $idValidation
     * @param string $hourValidation
     * @param Activity $activity
     */
    public function __construct(int $idValidation, string $hourValidation, Activity $activity)
    {
        $this->idValidation = $idValidation;
        $this->hourValidation = $hourValidation;
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

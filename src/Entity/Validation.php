<?php

namespace App\Entity;

use App\Repository\Impl\ValidationRepositoryImpl;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValidationRepositoryImpl::class)]
final class Validation
{
    #[ORM\Column]
    private int $idValidation;
    #[ORM\Column]
    private string $hourValidation;
    #[ORM\Column]
    private Activity $activity;
    #[ORM\Column]
    private Student $user;

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

    public function getUser(): Student
    {
        return $this->user;
    }

    public function setUser(Student $user): void
    {
        $this->user = $user;
    }

    /**
     * @param int $idValidation
     * @param string $hourValidation
     * @param Activity $activity
     * @param Student $user
     */
    public function __construct(int $idValidation, string $hourValidation, Activity $activity, Student $user)
    {
        $this->idValidation = $idValidation;
        $this->hourValidation = $hourValidation;
        $this->activity = $activity;
        $this->user = $user;
    }
}

<?php

namespace App\DTO;

use App\Entity\Student;

class UserDTO
{
    private Student $user;
    private int $totalScore;
    private int $totalScoreClasse;

    /**
     * @param Student $user
     * @param int $totalScore
     * @param int $totalScoreClasse
     */
    public function __construct(Student $user, int $totalScore, int $totalScoreClasse)
    {
        $this->user = $user;
        $this->totalScore = $totalScore;
        $this->totalScoreClasse = $totalScoreClasse;
    }


    public function withClassTotalScore(int $classTotalScore): self
    {
        return new self(
            $this->user,
            $this->totalScore,
            $classTotalScore
        );
    }

    public function getUser(): Student
    {
        return $this->user;
    }

    public function setUser(Student $user): void
    {
        $this->user = $user;
    }

    public function getTotalScore(): int
    {
        return $this->totalScore;
    }

    public function setTotalScore(int $totalScore): void
    {
        $this->totalScore = $totalScore;
    }

    public function getTotalScoreClasse(): int
    {
        return $this->totalScoreClasse;
    }

    public function setTotalScoreClasse(int $totalScoreClasse): void
    {
        $this->totalScoreClasse = $totalScoreClasse;
    }


}

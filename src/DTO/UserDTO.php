<?php

namespace DTO;

use Model\User;

class UserDTO
{
    private User $user;
    private int $totalScore;
    private int $totalScoreClasse;

    /**
     * @param User $user
     * @param int $totalScore
     */
    public function __construct(User $user, int $totalScore, int $totalScoreClasse)
    {
        $this->user = $user;
        $this->totalScore = $totalScore;
        $this->totalScoreClasse = $totalScoreClasse;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
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
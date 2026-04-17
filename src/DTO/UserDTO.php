<?php

namespace App\DTO;

use App\Entity\User;

class UserDTO
{
    private User $user;
    private int $totalScore;
    private int $totalScoreGroup;

    /**
     * @param User $user
     * @param int $totalScore
     * @param int $totalScoreGroup
     */
    public function __construct(User $user, int $totalScore, int $totalScoreGroup)
    {
        $this->user = $user;
        $this->totalScore = $totalScore;
        $this->totalScoreGroup = $totalScoreGroup;
    }


    public function withClassTotalScore(int $classTotalScore): self
    {
        return new self(
            $this->user,
            $this->totalScore,
            $classTotalScore
        );
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

    public function getTotalScoreGroup(): int
    {
        return $this->totalScoreGroup;
    }

    public function setTotalScoreGroup(int $totalScoreGroup): void
    {
        $this->totalScoreGroup = $totalScoreGroup;
    }


}

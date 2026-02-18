<?php

namespace DTO;

use Model\User;

class UserDTO
{
    private User $user;
    private int $totalScore;

    /**
     * @param User $user
     * @param int $totalScore
     */
    public function __construct(User $user, int $totalScore)
    {
        $this->user = $user;
        $this->totalScore = $totalScore;
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


}
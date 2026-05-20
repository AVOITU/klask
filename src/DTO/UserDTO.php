<?php

namespace App\DTO;

use App\Entity\User;

final readonly class UserDTO
{
    public function __construct(
        private User $user,
        private int $totalScore,
        private int $totalScoreGroup,
    ) {
    }

    public function withGroupTotalScore(int $totalScoreGroup): self
    {
        return new self(
            $this->user,
            $this->totalScore,
            $totalScoreGroup
        );
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getTotalScore(): int
    {
        return $this->totalScore;
    }

    public function getTotalScoreGroup(): int
    {
        return $this->totalScoreGroup;
    }
}

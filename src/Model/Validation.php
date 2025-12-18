<?php

namespace Model;

final class Validation
{
    private int $idValidation;
    private string $hourValidation;
    private Activity $activity;
    private User $user;

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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @param int $idValidation
     * @param string $hourValidation
     * @param Activity $activity
     * @param User $user
     */
    public function __construct(int $idValidation, string $hourValidation, Activity $activity, User $user)
    {
        $this->idValidation = $idValidation;
        $this->hourValidation = $hourValidation;
        $this->activity = $activity;
        $this->user = $user;
    }


}
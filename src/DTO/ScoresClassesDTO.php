<?php

namespace DTO;

class ScoresClassesDTO
{
    private string $school;
    private string $classroom;
    private int $scoreTotal;

    public function __construct(int $idClassroom, string $school, string $classroom, int $scoreTotal)
    {
        $this->school = $school;
        $this->classroom = $classroom;
        $this->scoreTotal = $scoreTotal;
    }

    public function getschool(): string
    {
        return $this->school;
    }

    public function getclassroom(): string
    {
        return $this->classroom;
    }

    public function getscoreTotal(): int
    {
        return $this->scoreTotal;
    } 
}
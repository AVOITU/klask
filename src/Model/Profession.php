<?php

namespace Model;


final class Profession
{
    /** @var Activity[] */
    private array $activities;

    private int $idProfession;
    private string $descriptionProfession;
    private string $codeRom;

    /**
     * @param Activity[] $activities
     * @param int $idProfession
     * @param string $descriptionProfession
     * @param string $codeRom
     */
    public function __construct(array $activities, int $idProfession, string $descriptionProfession, string $codeRom)
    {
        $this->activities = $activities;
        $this->idProfession = $idProfession;
        $this->descriptionProfession = $descriptionProfession;
        $this->codeRom = $codeRom;
    }

    public function getActivities(): array
    {
        return $this->activities;
    }

    public function setActivities(array $activities): void
    {
        $this->activities = $activities;
    }

    public function getIdProfession(): int
    {
        return $this->idProfession;
    }

    public function setIdProfession(int $idProfession): void
    {
        $this->idProfession = $idProfession;
    }

    public function getDescriptionProfession(): string
    {
        return $this->descriptionProfession;
    }

    public function setDescriptionProfession(string $descriptionProfession): void
    {
        $this->descriptionProfession = $descriptionProfession;
    }

    public function getCodeRom(): string
    {
        return $this->codeRom;
    }

    public function setCodeRom(string $codeRom): void
    {
        $this->codeRom = $codeRom;
    }


}
<?php

namespace Model;

final class Profession
{
    /**
     * @param Activity[] $activities
     */
    public function __construct(
        public int $idProfession,
        public string $descriptionProfession,
        public string $codeRom,
        public array $activities = [], // APPARTIENT (1,N)
    ) {}
}
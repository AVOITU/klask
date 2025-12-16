<?php

namespace Service;
interface InscriptionService
{
    public function getAllSchoolsAndClasses(): array;

    public function generateDefaultNickname(): string;

    public function registerStudent(array $post): array;
}
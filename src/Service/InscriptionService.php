<?php

namespace App\Service;
interface InscriptionService
{
    public function findDistinctSchools(): array;
    public function getClassesBySchool($school): array;
    public function generateDefaultNickname(): string;
    public function registerStudent(array $post): array;
}

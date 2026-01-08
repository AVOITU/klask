<?php

namespace Service;
interface InscriptionService
{
    public function getAllSchools(): array;
    public function getClassesBySchool($school): array;
    public function generateDefaultNickname(): string;
    public function registerStudent(array $post): array;
}
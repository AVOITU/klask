<?php

namespace Service;
interface InscriptionService
{
    public function getClassAndStudent(): array;

    public function generateDefaultNickname(): string;

    public function registerStudent(array $post): array;
}

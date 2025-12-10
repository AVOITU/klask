<?php

interface InscriptionService
{
    public function getClassAndStudent(): array;

    public function generateDefaultNickname(): string;

    public function handleRegistration(array $post): array;
}

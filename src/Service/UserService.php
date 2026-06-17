<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface UserService
{
    public function insertStudent(User $student): User;
    public function findById(int $id): ?User;
    public function createUserDTOById(int $idUser): ?UserDTO;
    // @return array<int, array{id: int, pseudo: string, score: int, pokedAt: ?int}>
    public function getStudentScoresByGroupCode(string $groupCode): array;
    public function pokeStudent(User $user): void;
    public function clearPoke(User $user): void;
    // @param array<string, int> $zoneRatings Zone name - rating (1-6)
    public function saveRatings(User $user, array $zoneRatings): void;

    // @return int[] ID des 3 sphères prioritaires
    public function getTopSphereIds(User $user): array;

    // @return int[] ID des 3 sphères les moins importantes
    public function getBottomSphereIds(User $user): array;

    public function checkSessionTimeout(User $user, SessionInterface $session): bool;
}

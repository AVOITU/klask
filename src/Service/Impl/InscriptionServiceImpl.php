<?php

namespace App\Service\Impl;

use App\Entity\Establishment;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\AuthorityRepository;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Service\InscriptionService;
use App\Service\UserService;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
class InscriptionServiceImpl implements InscriptionService
{
    private const ANIMALS = [
        'Dauphin', 'Goéland', 'Cormoran', 'Aigrette', 'Phoque', 'Hermine',
        'Coccinelle', 'Ragondin', 'Chevreuil', 'Sanglier',
        'Renard', 'Requin', 'Oursin', 'Crevette', 'Crabe',
        'Mérou', 'Sauterelle', 'Escargot', 'Crapaud', 'Salamandre',
    ];

    private const ADJECTIVES = [
        'du rêve', 'cosmique', 'magique', 'intrépide', 'cyber',
        'casse-cou', 'chic', 'perplexe', 'à lunettes', 'gastronome',
        'scolaire', 'globe-trotter', 'de la royauté', 'aquatique',
        'musicos', 'excentrique', 'des îles', 'cool', 'aristocrate', 'héroïque',
    ];

    private const GROUP_MAX_SIZE = 40;

    public function __construct(
        private readonly AuthorityRepository $authorityRepository,
        private readonly UserRepository $userRepository,
        private readonly GroupRepository $groupRepository,
        private readonly UserService $userService,
    ) {}

    public function generateUniquePseudo(): string
    {
        for ($i = 0; $i < 30; $i++) {
            $pseudo = $this->randomPseudo();
            if ($this->userRepository->findByPseudo($pseudo) === null) {
                return $pseudo;
            }
        }

        return $this->randomPseudo() . ' ' . time();
    }

    public function findGroupByCode(string $code): ?Group
    {
        return $this->groupRepository->findByCode($code);
    }

    public function validateGroupForRegistration(Group $group, Establishment $establishment, string $level): bool
    {
        return $group->getEstablishment()?->getId() === $establishment->getId()
            && $group->getName() === $level;
    }

    public function isGroupFull(Group $group): bool
    {
        return $this->groupRepository->countUsersByGroupId((int) $group->getId()) >= self::GROUP_MAX_SIZE;
    }

    public function registerStudent(User $student): User
    {
        $authority = $this->authorityRepository->findByRole('STUDENT');

        if ($authority === null) {
            throw new RuntimeException('Autorité STUDENT introuvable.');
        }

        $student->setAuthority($authority);

        if ($this->userRepository->findByPseudo((string) $student->getPseudo()) !== null) {
            $student->setPseudo($this->generateUniquePseudo());
        }

        return $this->userService->insertStudent($student);
    }

    private function randomPseudo(): string
    {
        return self::ANIMALS[array_rand(self::ANIMALS)]
            . ' '
            . self::ADJECTIVES[array_rand(self::ADJECTIVES)];
    }
}

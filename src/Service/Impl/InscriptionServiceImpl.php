<?php

namespace App\Service\Impl;

use App\Entity\User;
use App\Repository\AuthorityRepository;
use App\Service\ClassroomService;
use App\Service\InscriptionService;
use App\Service\UserService;
use RuntimeException;

require_once __DIR__ . '/../../../vendor/autoload.php';

class InscriptionServiceImpl implements InscriptionService
{
    private AuthorityRepository $authorityRepository;
    private UserService $userService;
    private ClassroomService $classroomService;

    private array $ANIMALS = [
        'Dauphin', 'Goéland', 'Cormoran', 'Aigrette', 'Phoque', 'Hermine',
        'Coccinelle', 'Ragondin', 'Chevreuil', 'Sanglier',
        'Renard', 'Requin', 'Oursin', 'Crevette', 'Crabe',
        'Mérou', 'Sauterelle', 'Escargot', 'Crapaud', 'Salamandre',
    ];
    private array $ADJECTIVES = [
        'du rêve', 'cosmique', 'magique', 'intrépide', 'cyber',
        'casse-cou', 'chic', 'perplexe', 'à lunettes', 'gastronome',
        'scolaire', 'globe-trotter', 'de la royauté', 'aquatique',
        'musicos', 'excentrique', 'des îles', 'cool', 'aristocrate', 'héroïque',
    ];

    /**
     * @param AuthorityRepository $authorityRepository
     * @param UserService $userService
     * @param ClassroomService $classroomService
     */
    public function __construct(AuthorityRepository $authorityRepository, UserService $userService, ClassroomService $classroomService)
    {
        $this->authorityRepository = $authorityRepository;
        $this->userService = $userService;
        $this->classroomService = $classroomService;
    }

    public function findDistinctSchools(): array {
        return $this->classroomService->findDistinctSchools();
    }

    public function generateDefaultNickname(): string
    {
        $animal = $this->ANIMALS[array_rand($this->ANIMALS)];
        $adjectif = $this->ADJECTIVES[array_rand($this->ADJECTIVES)];
        return $animal . ' ' . $adjectif;
    }

    public function registerStudent(User $student): User
    {
        $authority = $this->authorityRepository->findByRole('STUDENT');

        if ($authority === null) {
            throw new RuntimeException('Autorité STUDENT introuvable.');
        }

        $student->setAuthority($authority);

        return $this->userService->insertStudent($student);
    }
}

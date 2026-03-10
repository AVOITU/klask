<?php

namespace App\Service\Impl;

use App\Entity\Classroom;
use App\Entity\User;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Security\Role;
use App\Service\AuthorityService;
use App\Service\ClassroomService;
use App\Service\InscriptionService;
use App\Service\UserService;

require_once __DIR__ . '/../../../vendor/autoload.php';

class InscriptionServiceImpl implements InscriptionService
{
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

    public function __construct(
        private readonly ClassroomService $classRoomService,
        private readonly UserService      $userService,
    ) { }

    public function findDistinctSchools(): array {
        return $this->classRoomService->findDistinctSchools();
    }

    public function getClassesBySchool($school): array {
        return $this->classRoomService->findClassesBySchool($school);
    }

    public function generateDefaultNickname(): string
    {
        $animal = $this->ANIMALS[array_rand($this->ANIMALS)];
        $adjectif = $this->ADJECTIVES[array_rand($this->ADJECTIVES)];
        return $animal . ' ' . $adjectif;
    }

    public function registerStudent(User $student): User
    {
        return $this->userService->insertStudent($student);
    }
}

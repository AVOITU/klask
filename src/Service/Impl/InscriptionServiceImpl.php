<?php

namespace App\Service\Impl;

use App\Entity\Classroom;
use App\Entity\Student;
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
        private ClassroomService $classRoomService,
        private UserService      $userService,
        private AuthorityService $authorityService
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

    public function registerStudent(array $post): array
    {
        $messageSuccess = null;
        $messageError   = null;

        $classId = isset($post['classe_final_id']) ? (int)$post['classe_final_id'] : 0;
        $pseudo  = isset($post['pseudo_choisi'])
            ? htmlspecialchars($post['pseudo_choisi'], ENT_QUOTES, 'UTF-8')
            : 'Anonyme';

        if ($classId <= 0) {
            return [null, 'Classe invalide.'];
        }

        try {
            /** @var Classroom|null $classRoom */
            $classRoom = $this->classRoomService->findById($classId);

            if ($classRoom === null) {
                return [null, 'Classe introuvable.'];
            }

            $authority =  $this->authorityService->findByRole(Role::STUDENT->value);

            $student = new Student(
                validations: [],
                authority: $authority,
                idUser: (int)null,
                pseudoUser: $pseudo,
                classRoom: $classRoom
            );

            $this->userService->insertStudent($student);

            $messageSuccess = [
                'ecole'  => $classRoom->getSchool(),
                'classe' => $classRoom->getClassName(),
                'pseudo' => $student->getPseudoUser(),
            ];

        } catch (UniqueConstraintViolationException $e) {
            $messageError = '⚠️ Ce pseudo est déjà pris ! Relancez le dé.';
        } catch (Exception $e) {
            $messageError = 'Erreur lors de l’inscription.';
        }

        return [$messageSuccess, $messageError];
    }
}

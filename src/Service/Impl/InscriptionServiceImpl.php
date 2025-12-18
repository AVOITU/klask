<?php

namespace Service\Impl;

use Model\ClassRoom;
use Model\User;
use Service\ClassRoomService;
use Service\InscriptionService;
use PDOException;
use Repository\UserRepository;
use Service\UserService;

require_once __DIR__ . '/../../../vendor/autoload.php';

class InscriptionServiceImpl implements InscriptionService
{
    private array $ANIMALS = [
        'Loutre', 'Panda', 'Renard', 'Loup', 'Hibou',
        'Dauphin', 'Faucon', 'Lynx', 'Salamandre', 'Koala',
        'Dragon', 'Phoenix', 'Griffon'
    ];

    private array $ADJECTIVES = [
        'Cosmique', 'Solaire', 'Zen', 'Rapide', 'Agile',
        'Sage', 'Intrépide', 'Loyal', 'Magique', 'Epique'
    ];

    public function __construct(
        private ClassRoomService $classRoomService,
        private UserService $userService,
    ) { }

    public function getAllSchoolsAndClasses(): array {
        return $this->classRoomService->getAllSchoolsAndClasses();
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
            /** @var ClassRoom|null $classRoom */
            $classRoom = $this->classRoomService->findById($classId);

            if ($classRoom === null) {
                return [null, 'Classe introuvable.'];
            }

            $student = new User(
                validations: [],
                authorities: [],
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

        } catch (PDOException $e) {
            $messageError = ($e->getCode() === '23000')
                ? '⚠️ Ce pseudo est déjà pris ! Relancez le dé.'
                : 'Erreur lors de l’inscription.';
        }
        return [$messageSuccess, $messageError];
    }
}
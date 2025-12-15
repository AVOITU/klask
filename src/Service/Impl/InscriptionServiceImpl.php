<?php

namespace Service\Impl;

use Model\ClassRoom;
use Model\User;
use Service\InscriptionService;
use PDOException;
use Repository\ClassRoomRepository;
use Repository\UserRepository;

require_once __DIR__ . '/../InscriptionService.php';

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
        private ClassRoomRepository $classeRepo,
        private UserRepository      $userRepo,
    ) { }

    public function getClassAndStudent(): array { return $this->classeRepo->findAll(); }

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
            // 1) on récupère la classe en OBJET (Model)
            /** @var ClassRoom|null $classRoom */
            $classRoom = $this->classeRepo->findById($classId);

            if ($classRoom === null) {
                return [null, 'Classe introuvable.'];
            }

            $student = new User(
                validations: [],
                authorities: [],
                idUser: (int)null,
                pseudoUser: $pseudo,
                classe: $classRoom
            );

            $this->userRepo->insertStudent($student);

            // 3) messageSuccess construit depuis getters
            $messageSuccess = [
                'ecole'  => $classRoom->getSchool(),
                'classe' => $classRoom->getNameClass(),
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
<?php

namespace Service\Impl;

use Service\InscriptionService;
use PDOException;
use Repository\ClassRoomRepository;
use Repository\UserRepository;

require_once __DIR__ . '/../InscriptionService.php';

class InscriptionServiceImpl implements InscriptionService
{
    private array $animaux = [
        'Loutre', 'Panda', 'Renard', 'Loup', 'Hibou',
        'Dauphin', 'Faucon', 'Lynx', 'Salamandre', 'Koala',
        'Dragon', 'Phoenix', 'Griffon'
    ];

    private array $adjectifs = [
        'Cosmique', 'Solaire', 'Zen', 'Rapide', 'Agile',
        'Sage', 'Intrépide', 'Loyal', 'Magique', 'Epique'
    ];

    public function __construct(
        private ClassRoomRepository $classeRepo,
        private UserRepository      $userRepo,
    ) { }

    public function getClassAndStudent(): array
    {
        $classes = $this->classeRepo->findAll();
        $ecoles = [];

        foreach ($classes as $row) {
            if (!empty($row['ecole'])) {
                $ecoles[$row['ecole']] = $row['ecole'];
            }
        }

        return [$classes, $ecoles];
    }

    public function generateDefaultNickname(): string
    {
        $animal = $this->animaux[array_rand($this->animaux)];
        $adjectif = $this->adjectifs[array_rand($this->adjectifs)];
        return $animal . ' ' . $adjectif;
    }

    public function handleRegistration(array $post): array
    {
        $messageSuccess = null;
        $messageError   = null;

        $classId = isset($post['classe_final_id']) ? (int)$post['classe_final_id'] : 0;
        $pseudo  = isset($post['pseudo_choisi'])
            ? htmlspecialchars($post['pseudo_choisi'], ENT_QUOTES, 'UTF-8')
            : 'Anonyme';

        if ($classId <= 0) {
            $messageError = 'Classe invalide.';
            return [$messageSuccess, $messageError];
        }

        try {
            $this->userRepo->insertStudent($pseudo, $classId);

            $classInfo = $this->classeRepo->findById($classId);

            if ($classInfo) {
                $messageSuccess = [
                    'ecole' => $classInfo['ecole'],
                    'classe' => $classInfo['nom_classe'],
                    'pseudo' => $pseudo,
                ];
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $messageError = '⚠️ Ce pseudo est déjà pris ! Relancez le dé.';
            } else {
                $messageError = 'Erreur lors de l’inscription.';
            }
        }
        return [$messageSuccess, $messageError];
    }
}
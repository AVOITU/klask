<?php


namespace Repository\Impl;

use PDO;
use Repository\UserRepository;

require_once __DIR__ . '/../UserRepository.php';
class UserRepositoryImpl implements UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function insertStudent(string $pseudo, int $id_classe): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO utilisateur 
             (pseudo_utilisateur, role_utilisateur, autorite_utilisateur, id_classe)
             VALUES (:pseudo, 'Elève', 'Aucune', :id_classe)"
        );

        $stmt->execute([
            'pseudo' => $pseudo,
            'id_classe' => $id_classe,
        ]);
    }
}
<?php


namespace Repository\Impl;

use Model\User;
use PDO;
use Repository\UserRepository;

require_once __DIR__ . '/../UserRepository.php';
class UserRepositoryImpl implements UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function findById(int $id_user): ?User
    {
        $SQL = "SELECT id_utilisateur, pseudo_utilisateur, id_classe FROM utilisateur WHERE id_classe = :id";
        $stmt = $this->pdo->prepare($SQL);

        $stmt->execute(['id' => $id_user]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new User(
            [],
            [],
            (int)$row['id_utilisateur'],
            $row['pseudo_utilisateur'],
            $row['id_classe'],
        );
    }

    public function insertStudent(User $user): User
    {
        $id_classe = $user->getIdUser();
        $pseudo = $user->getPseudoUser();

        $stmt = $this->pdo->prepare(
            "INSERT INTO utilisateur 
             (pseudo_utilisateur, role_utilisateur, autorite_utilisateur, id_classe)
             VALUES (:pseudo, 'Elève', 'Aucune', :id_classe)"
        );

        $stmt->execute(
            ['id_classe' => $id_classe,
            'pseudo' => $pseudo]
        );

        return $this->findById($id_classe);
    }
}
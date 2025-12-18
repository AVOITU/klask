<?php


namespace Repository\Impl;

use Model\User;
use PDO;
use Repository\UserRepository;

require_once __DIR__ . '/../../../vendor/autoload.php';
class UserRepositoryImpl implements UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function findById(int $id_user): ?User
    {
        $SQL = "
        SELECT 
            u.id_utilisateur, u.pseudo_utilisateur, u.id_classe,
            COALESCE(JSON_ARRAYAGG(a.nom_autorite), JSON_ARRAY()) AS authorities
        FROM utilisateur u
        LEFT JOIN utilisateur_autorite ua ON ua.id_utilisateur = u.id_utilisateur
        LEFT JOIN autorite a ON a.id_autorite = ua.id_autorite
        WHERE u.id_utilisateur = :id
        GROUP BY u.id_utilisateur
    ";
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
        $id_user =$user->getIdUser();
        $pseudo = $user->getPseudoUser();
        $id_classe = $user->getClassRoom()->getIdClass();

        $stmt = $this->pdo->prepare(
            "INSERT INTO utilisateur 
             (id_utilisateur,pseudo_utilisateur, id_classe)
             VALUES (:id_utilisateur, :pseudo, :id_classe)"
        );

        $stmt->execute(
            ['id_utilisateur' => $id_user,
            'pseudo' => $pseudo,
            'id_classe' => $id_classe]
        );
        return $this->findById($id_classe);
    }
}
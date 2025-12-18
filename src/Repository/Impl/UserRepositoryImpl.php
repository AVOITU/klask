<?php


namespace Repository\Impl;

use Model\ClassRoom;
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
                  u.id_utilisateur,
                  u.pseudo_utilisateur,
                  c.id_classe,
                  c.ecole,
                  c.nom_classe,
                  COALESCE(JSON_ARRAYAGG(a.nom_autorite), JSON_ARRAY()) AS authorities
                FROM utilisateur u
                JOIN classe c ON c.id_classe = u.id_classe
                LEFT JOIN utilisateur_autorite ua ON ua.id_utilisateur = u.id_utilisateur
                LEFT JOIN autorite a ON a.id_autorite = ua.id_autorite
                WHERE u.id_utilisateur = :id
                GROUP BY u.id_utilisateur, c.id_classe, c.ecole, c.nom_classe
            ";
        $stmt = $this->pdo->prepare($SQL);

        $stmt->execute(['id' => $id_user]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $classRoom = new ClassRoom(
            users: [],
            idClass: (int)$row['id_classe'],
            school: $row['ecole'],
            className: $row['nom_classe']
        );

        return new User(
            validations: [],
            authorities: [],
            idUser: (int)$row['id_utilisateur'],
            pseudoUser: $row['pseudo_utilisateur'],
            classRoom: $classRoom
        );
    }

    public function insertStudent(User $user): User
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO utilisateur (pseudo_utilisateur, id_classe)
         VALUES (:pseudo, :id_classe)"
        );

        $stmt->execute([
            'pseudo'    => $user->getPseudoUser(),
            'id_classe' => $user->getClassRoom()->getIdClass(),
        ]);

        $newId = (int)$this->pdo->lastInsertId();
        return $this->findById($newId);
    }
}
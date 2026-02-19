<?php

namespace App\Repository\Impl;

use App\DTO\UserDTO;
use App\Entity\Authority;
use App\Entity\ClassRoom;
use App\Entity\User;
use PDO;
use App\Repository\UserRepository;

require_once __DIR__ . '/../../../vendor/autoload.php';

class UserRepositoryImpl implements UserRepository
{
    public function __construct(private PDO $pdo) {}

    public function findById(int $idUser): ? User
    {
                $SQL = "
                        SELECT
                          u.id_user,
                          u.pseudo_user,
                          c.id_class,
                          c.school,
                          c.name_class,
                          a.id_authority,
                          a.role_user,
                          a.authority_user
                        FROM users u
                        JOIN classes c                      ON c.id_class = u.id_class
                        JOIN authorities a                  ON a.id_authority = u.id_authority
                        LEFT JOIN validations v             ON v.id_user = u.id_user
                        LEFT JOIN activities act            ON act.id_activity = v.id_activity
                        LEFT JOIN activity_categories cat   ON cat.id_category = act.id_category
                        WHERE u.id_user = :id
                        ";

        $stmt = $this->pdo->prepare($SQL);
        $stmt->execute(['id' => $idUser]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $authority = new Authority(
            users: [],
            idAuthority: (int) $row['id_authority'],
            roleUser: $row['role_user'],
            authorityUser: $row['authority_user']
        );

        $classRoom = new ClassRoom(
            users: [],
            idClass: (int)$row['id_class'],
            school: $row['school'],
            className: $row['name_class']
        );

        return $user = new User(
            validations: [],
            authority: $authority,
            idUser: (int)$row['id_user'],
            pseudoUser: $row['pseudo_user'],
            classRoom: $classRoom
        );
    }

    public function insertStudent(User $user): User
    {
        $SQL = "
                INSERT INTO users (pseudo_user, id_class, id_authority)
                VALUES (
                  :pseudo,
                  :id_class,
                  :id_authority
                );
        ";

        $stmt = $this->pdo->prepare($SQL);
        $stmt->execute([
            'pseudo'   => $user->getPseudoUser(),
            'id_class' => $user->getClassRoom()->getIdClass(),
            'id_authority' => $user->getAuthority()->getIdAuthority()
        ]);

        $newId = (int)$this->pdo->lastInsertId();
        return $this->findById($newId);
    }

    public function createUserDTOById(int $idUser): ? UserDTO
    {
        $SQL = "
                SELECT
                  u.id_user,
                  u.pseudo_user,
                  c.id_class,
                  c.school,
                  c.name_class,
                  a.id_authority,
                  a.role_user,
                  a.authority_user,
                  COALESCE(SUM(cat.nbr_point), 0) AS score_total,
                  COUNT(v.id_validation) AS validations_count,

                  (
                    SELECT COALESCE(SUM(cat2.nbr_point),0)
                    FROM users u2
                    LEFT JOIN validations v2 ON v2.id_user = u2.id_user
                    LEFT JOIN activities act2 ON act2.id_activity = v2.id_activity
                    LEFT JOIN activity_categories cat2 ON cat2.id_category = act2.id_category
                    WHERE u2.id_class = u.id_class
                  ) AS class_total_score

                FROM users u
                JOIN classes c ON c.id_class = u.id_class
                JOIN authorities a ON a.id_authority = u.id_authority
                LEFT JOIN validations v ON v.id_user = u.id_user
                LEFT JOIN activities act ON act.id_activity = v.id_activity
                LEFT JOIN activity_categories cat ON cat.id_category = act.id_category
                WHERE u.id_user = :id
                GROUP BY
                  u.id_user, u.pseudo_user,
                  c.id_class, c.school, c.name_class,
                  a.id_authority, a.role_user, a.authority_user;
                    ";

        $stmt = $this->pdo->prepare($SQL);
        $stmt->execute(['id' => $idUser]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $authority = new Authority(
            users: [],
            idAuthority: (int) $row['id_authority'],
            roleUser: $row['role_user'],
            authorityUser: $row['authority_user']
        );

        $classRoom = new ClassRoom(
            users: [],
            idClass: (int)$row['id_class'],
            school: $row['school'],
            className: $row['name_class']
        );

        $totalScore = (int)$row['score_total'];

        $totalScoreClasse = (int)$row['class_total_score'];

        $user = new User(
            validations: [],
            authority: $authority,
            idUser: (int)$row['id_user'],
            pseudoUser: $row['pseudo_user'],
            classRoom: $classRoom
        );

        return new UserDTO(
            user : $user,
            totalScore: $totalScore,
            totalScoreClasse: $totalScoreClasse
        );
    }
}

<?php

namespace Repository\Impl;

use Model\Authority;
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
                u.id_user,
                u.pseudo_user,
                c.id_class,
                c.school,
                c.name_class,
                a.id_authority,
                a.role_user,
                a.authority_user
            FROM users u
            INNER JOIN classes c ON c.id_class = u.id_class
            INNER JOIN klask.authorities a on u.id_authority = a.id_authority
            WHERE u.id_user = :id
        ";

        $stmt = $this->pdo->prepare($SQL);
        $stmt->execute(['id' => $id_user]);

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

        return new User(
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
}
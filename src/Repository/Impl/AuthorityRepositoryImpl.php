<?php

namespace Repository\Impl;

use Model\Authority;
use PDO;
use Repository\AuthorityRepository;

require_once __DIR__ . '/../../../vendor/autoload.php';
class AuthorityRepositoryImpl implements AuthorityRepository
{
    public function __construct(private PDO $pdo) {}
    public function findByRole(string $role) : ?Authority
    {
        $SQL = "
            SELECT id_authority, role_user, authority_user FROM authorities
            WHERE role_user = :role_user
        ";

        $stmt = $this->pdo->prepare($SQL);
        $stmt->execute(['role_user' => $role]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Authority(
            users: [],
            idAuthority: (int) $row['id_authority'],
            roleUser: $row['role_user'],
            authorityUser: $row['authority_user']
        );
    }
}
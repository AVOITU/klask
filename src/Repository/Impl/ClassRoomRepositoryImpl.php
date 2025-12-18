<?php

namespace Repository\Impl;

use Model\ClassRoom;
use PDO;
use Repository\ClassRoomRepository;

require_once __DIR__ . '/../../../vendor/autoload.php';

class ClassRoomRepositoryImpl implements ClassRoomRepository
{
    public function __construct(private PDO $pdo) { }

    public function findAllSchools(): array {
        $stmt = $this->pdo->query("SELECT DISTINCT ecole FROM classe ORDER BY ecole");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function findClassesBySchool(string $school): array {
        $stmt = $this->pdo->prepare("
        SELECT id_classe, nom_classe
        FROM classe
        WHERE ecole = :school
        ORDER BY nom_classe
    ");
        $stmt->execute(['school' => $school]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id_classe): ?ClassRoom
    {
        $SQL = "SELECT id_classe, ecole, nom_classe FROM classe WHERE id_classe = :id";
        $stmt = $this->pdo->prepare($SQL);
        $stmt->execute(['id' => $id_classe]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new ClassRoom(
            users : [],
            idClass: (int)$row['id_classe'],
            school: $row['ecole'],
            className: $row['nom_classe']
        );
    }
}
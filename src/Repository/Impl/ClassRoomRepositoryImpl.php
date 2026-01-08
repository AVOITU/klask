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
        $stmt = $this->pdo->query("SELECT DISTINCT school FROM classes ORDER BY school");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function findClassesBySchool(string $school): array {
        $stmt = $this->pdo->prepare("
        SELECT id_class, name_class
        FROM classes
        WHERE school = :school
        ORDER BY name_class
    ");
        $stmt->execute(['school' => $school]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id_class): ?ClassRoom
    {
        $SQL = "SELECT id_class, school, name_class FROM classes WHERE id_class = :id";
        $stmt = $this->pdo->prepare($SQL);
        $stmt->execute(['id' => $id_class]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new ClassRoom(
            users : [],
            idClass: (int)$row['id_class'],
            school: $row['school'],
            className: $row['name_class']
        );
    }
}
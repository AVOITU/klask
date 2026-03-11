<?php

namespace Repository\Impl;

use DTO\ScoresClassesDTO;
use Model\ClassRoom;
use PDO;
use Repository\ClassRoomRepository;

require_once __DIR__ . '/../../../vendor/autoload.php';

class ClassRoomRepositoryImpl implements ClassRoomRepository
{
    public function __construct(private PDO $pdo) {}

    public function findAllSchools(): array
    {
        $stmt = $this->pdo->query("SELECT DISTINCT school FROM classes ORDER BY school");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getScoresByClass(): array
    {
        $stmt = $this->pdo->query("
            SELECT 
                c.id_class AS idClassroom,
                c.school AS school,
                c.name_class AS classroom,
                COALESCE(SUM(ac.nbr_point), 0) AS scoreTotal
            FROM classes c
            LEFT JOIN users u 
                ON u.id_class = c.id_class
            LEFT JOIN validations v 
                ON v.id_user = u.id_user
            LEFT JOIN activities a 
                ON a.id_activity = v.id_activity
            LEFT JOIN activity_categories ac 
                ON ac.id_category = a.id_category
            GROUP BY 
                c.id_class, 
                c.school, 
                c.name_class
            ORDER BY scoreTotal DESC
            LIMIT 5
        ");

        $results = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new ScoresClassesDTO(
                $row['idClassroom'],
                $row['school'],
                $row['classroom'],
                $row['scoreTotal']
            );
        }

        return $results;
    }

    public function findClassesBySchool(string $school): array
    {
        $stmt = $this->pdo->prepare("
        SELECT id_class, name_class
        FROM classes
        WHERE school = :school
        ORDER BY name_class
    ");
        $stmt->execute(['school' => $school]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $idClass): ?ClassRoom
    {
        $SQL = "SELECT id_class, school, name_class FROM classes WHERE id_class = :id";
        $stmt = $this->pdo->prepare($SQL);
        $stmt->execute(['id' => $idClass]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new ClassRoom(
            users: [],
            idClass: (int)$row['id_class'],
            school: $row['school'],
            className: $row['name_class']
        );
    }
}

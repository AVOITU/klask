<?php

namespace Repository\Impl;

use Model\ClassRoom;
use PDO;
use Repository\ClassRoomRepository;

require_once __DIR__ . '/../../../vendor/autoload.php';

class ClassRoomRepositoryImpl implements ClassRoomRepository
{
    public function __construct(private PDO $pdo) { }

    public function findAll(): array
    {
        $SQL = "SELECT id_classe, ecole, nom_classe
            FROM classe
            ORDER BY ecole, nom_classe";

        $stmt = $this->pdo->query($SQL);
        // Doit retourner un tableau d'objets donc on boucle sur le résultat pour remplir le tableau de Classes
        $classRooms = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $classRooms[] = new ClassRoom(
                $row[],
                $row['id_classe'],
                $row['ecole'],
                $row['nom_classe']
            );
        }
        return $classRooms;
    }

    public function findById(int $id_classe): ?ClassRoom
    {
        $SQL = "SELECT ecole, nom_classe FROM classe WHERE id_classe = :id";

        $stmt = $this->pdo->prepare($SQL);

        $stmt->execute(['id' => $id_classe]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new ClassRoom(
            [],
            (int)$row['id_classe'],
            $row['ecole'],
            $row['nom_classe']
        );
    }
}
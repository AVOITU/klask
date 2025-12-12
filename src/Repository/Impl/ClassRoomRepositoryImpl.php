<?php

namespace Repository\Impl;

use PDO;
use Repository\ClassRoomRepository;

require_once __DIR__ . '/../ClassRoomRepository.php';

class ClassRoomRepositoryImpl implements ClassRoomRepository
{
    public function __construct(private PDO $pdo) { }

    public function findAll(): array
    {
        $sql = "SELECT id_classe, ecole, nom_classe 
                FROM classe 
                ORDER BY ecole , nom_classe";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id_classe): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT ecole, nom_classe FROM classe WHERE id_classe = :id"
        );
        $stmt->execute(['id' => $id_classe]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
}
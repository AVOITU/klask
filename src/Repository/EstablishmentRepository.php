<?php

namespace App\Repository;

use App\Entity\Establishment;
use Doctrine\ORM\QueryBuilder;

interface EstablishmentRepository
{
    public function findDistinctEstablishments(): array;
    public function qbByEstablishment(?string $establishment): QueryBuilder;
    // public function findById(int $id): ?Establisment;

    //public function findClassTotalScore(int $classId): int;
}
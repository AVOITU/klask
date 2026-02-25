<?php

namespace App\Repository\Impl;

use App\Entity\Classroom;
use App\Repository\ClassroomRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

require_once __DIR__ . '/../../../vendor/autoload.php';

class ClassroomRepositoryImpl extends ServiceEntityRepository implements ClassroomRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Classroom::class);
    }

    public function findDistinctSchools(): array
    {
        return $this->createQueryBuilder('c')
            ->select('DISTINCT c.school')
            ->orderBy('c.school', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function findClassesBySchool(string $school): array {
        return $this->createQueryBuilder('c')
            ->select('c.idClass', 'c.nameClass')
            ->where('c.school = :school')
            ->setParameter('school', $school)
            ->orderBy('c.nameClass', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    public function findById(int $idClass): ?Classroom
    {
        return $this->createQueryBuilder('c')
            ->where('c.idClass = :idClass')
            ->setParameter('idClass', $idClass)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

<?php

namespace App\Repository\Impl;

use App\Entity\Classroom;
use App\Repository\ClassroomRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function qbBySchool(?string $school): QueryBuilder
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.className', 'ASC');

        if ($school) {
            $qb->andWhere('c.school = :school')
                ->setParameter('school', $school);
        } else {
            $qb->andWhere('1 = 0');
        }

        return $qb;
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

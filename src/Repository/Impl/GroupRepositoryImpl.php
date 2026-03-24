<?php

namespace App\Repository\Impl;

use App\Entity\Group;
use App\Repository\GroupRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class GroupRepositoryImpl extends ServiceEntityRepository implements GroupRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
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
            ->orderBy('c.nameClass', 'ASC');

        if ($school) {
            $qb->andWhere('c.school = :school')
                ->setParameter('school', $school);
        } else {
            $qb->andWhere('1 = 0');
        }

        return $qb;
    }

    public function findById(int $idClass): ?Group
    {
        return $this->createQueryBuilder('c')
            ->where('c.idClass = :idClass')
            ->setParameter('idClass', $idClass)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findClassTotalScore(int $classId): int
    {
        return (int) $this->createQueryBuilder('u')
            ->select('COALESCE(SUM(cat.nbrPoint), 0)')
            ->leftJoin('u.validations', 'v')
            ->leftJoin('v.activity', 'act')
            ->leftJoin('act.category', 'cat')
            ->where('u.classRoom = :classId')
            ->setParameter('classId', $classId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}

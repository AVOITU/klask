<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    /*public function qbByEstablishment(?string $establishment): QueryBuilder
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
    */

public function qbByEstablishment(?string $establishmentName): QueryBuilder
{
    $qb = $this->createQueryBuilder('g')
        ->join('g.establishment', 'e')
        ->orderBy('g.nameGroup', 'ASC');

    if ($establishmentName) {
        $qb->andWhere('e.name_establishment = :establishment')
           ->setParameter('establishment', $establishmentName);
    } else {
        $qb->andWhere('1 = 0');
    }

    return $qb;
}

    public function findById(int $idGroup): ?Group
    {
        return $this->createQueryBuilder('g')
            ->where('g.id = :idGroup')
            ->setParameter('idGroup', $idGroup)
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function findGroupTotalScore(int $groupId): int
    {
        return (int) $this->createQueryBuilder('u')
            ->select('COALESCE(SUM(cat.nbrPoint), 0)')
            ->leftJoin('u.validations', 'v')
            ->leftJoin('v.activity', 'act')
            ->leftJoin('act.category', 'cat')
            ->where('u.group = :groupId')
            ->setParameter('groupId', $groupId)
            ->getQuery()
            ->getSingleScalarResult();
    }

}

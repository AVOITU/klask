<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    // queryBuilder par établissement, plus utilisé (remplacé par code groupe). Garder pour les tests.
    public function qbByEstablishment(?string $establishmentName): QueryBuilder
    {
        $qb = $this->createQueryBuilder('g')
            ->join('g.establishment', 'e')
            ->orderBy('g.name', 'ASC');

        if ($establishmentName) {
            $qb->andWhere('e.name = :establishment')
               ->setParameter('establishment', $establishmentName);
        } else {
            $qb->andWhere('1 = 0');
        }

        return $qb;
    }

    // niveaux distincts en bdd pour le select du formulaire d'inscription
    /** @return string[] */
    public function findDistinctLevels(): array
    {
        $rows = $this->createQueryBuilder('g')
            ->select('DISTINCT g.name')
            ->where('g.name IS NOT NULL')
            ->orderBy('g.name', 'ASC')
            ->getQuery()
            ->getScalarResult();

        return array_column($rows, 'name');
    }

    // groupe par code unique saisi à l'inscription
    public function findByCode(string $code): ?Group
    {
        return $this->findOneBy(['code' => strtoupper(trim($code))]);
    }

    public function countUsersByGroupId(int $groupId): int
    {
        return (int) $this->createQueryBuilder('g')
            ->select('COUNT(u.id)')
            ->join('g.users', 'u')
            ->where('g.id = :groupId')
            ->setParameter('groupId', $groupId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // somme des points de tous les scans des membres du groupe
    public function findGroupTotalScore(int $groupId): int
    {
        return (int) $this->createQueryBuilder('g')
            ->select('COALESCE(SUM(cat.nbrPoints), 0)')
            ->join('g.users', 'u')
            ->join('u.scans', 's')
            ->join('s.activity', 'act')
            ->join('act.category', 'cat')
            ->where('g.id = :groupId')
            ->setParameter('groupId', $groupId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}

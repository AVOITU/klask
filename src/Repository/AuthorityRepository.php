<?php

namespace App\Repository;

use App\Entity\Authority;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AuthorityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Authority::class);
    }

    public function findByRole(string $roleName): ?Authority
        {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.authorityRoles', 'ar') // On passe par la table de liaison
            ->innerJoin('ar.role', 'r')           // On rejoint la table Role
            ->andWhere('r.nameRole = :roleName')  // On filtre sur le nom du rôle
            ->setParameter('roleName', $roleName)
            ->getQuery()
            ->getOneOrNullResult();
        }
}

<?php

namespace App\Repository\Impl;

use App\Entity\Authority;
use App\Repository\AuthorityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AuthorityRepositoryImpl extends ServiceEntityRepository implements AuthorityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Authority::class);
    }

    public function findByRole(string $role): ?Authority
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.roleUser = :role')
            ->setParameter('role', $role)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

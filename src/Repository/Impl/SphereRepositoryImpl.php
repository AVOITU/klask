<?php

namespace App\Repository\Impl;

use App\Entity\Sphere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\SphereRepository;

/**
 * @extends ServiceEntityRepository<Sphere>
 */
class SphereRepositoryImpl extends ServiceEntityRepository implements SphereRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sphere::class);
    }

    /**
     * @return Sphere[]
     */
    public function findAllWithActivities(): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.activities', 'a') 
            ->addSelect('a') 
            ->orderBy('s.nameSphere', 'ASC') 
            ->getQuery()
            ->getResult();
    }
}
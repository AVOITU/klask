<?php

namespace App\Repository\Impl;

use App\Entity\Establishment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Establishment>
 */
class EstablishmentRepositoryImpl extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Establishment::class);
    }

    public function findDistinctEstablishments(): array
    {
        return $this->createQueryBuilder('est')
            ->select('DISTINCT est.name_establishment')
            ->orderBy('est.name_establishment', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }
}

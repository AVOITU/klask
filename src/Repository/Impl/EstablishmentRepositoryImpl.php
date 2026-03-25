<?php

namespace App\Repository\Impl;

use App\Entity\Establishment;
use App\Repository\EstablishmentRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class EstablishmentRepositoryImpl extends ServiceEntityRepository implements EstablishmentRepository
{
    public function findDistinctEstablishments(): array
    {
        return $this->createQueryBuilder('est')
            ->select('DISTINCT est.name_establishment')
            ->orderBy('est.name_establishment', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }
}
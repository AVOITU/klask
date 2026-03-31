<?php

namespace App\Repository\Impl;

use App\Entity\Establishment;
use App\Repository\EstablishmentRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Establishment>
 */
class EstablishmentRepositoryImpl extends ServiceEntityRepository implements EstablishmentRepository
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

      public function qbByEstablishment(?string $establishment): QueryBuilder
    {
        $qb = $this->createQueryBuilder('g')
            ->innerJoin('g.establishment', 'est')
            ->orderBy('g.nameGroup', 'ASC');
            

        if ($establishment) {
            $qb->andWhere('est.name_establishment = :name_establishment')
                ->setParameter('establishment', $establishment);
        } else {
            $qb->andWhere('1 = 0');
        }

        return $qb;
    }
    
}

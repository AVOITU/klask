<?php

namespace App\Repository;

use App\Entity\Establishment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Establishment>
 */
class EstablishmentRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Establishment::class);
        $this->em = $em;
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
            ->orderBy('g.name_establishment', 'ASC');


        if ($establishment) {
            $qb->andWhere('est.name_establishment = :name_establishment')
                ->setParameter('establishment', $establishment);
        } else {
            $qb->andWhere('1 = 0');
        }

        return $qb;
    }

    public function saveEstablishmentByName(String $establishmentName): void
    {
        $establishment = new Establishment();
        $establishment->setNameEstablishment($establishmentName);

        $this->em->persist($establishment);
        $this->em->flush();
    }
}

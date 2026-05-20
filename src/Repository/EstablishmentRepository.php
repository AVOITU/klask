<?php

namespace App\Repository;

use App\Entity\Establishment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// EntityManagerInterface retiré — saveEstablishmentByName commentée
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Establishment> */
class EstablishmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Establishment::class);
    }

    // noms établissements triés pour formulaire d'inscription
    public function findDistinctEstablishments(): array
    {
        return $this->createQueryBuilder('est')
            ->select('DISTINCT est.name')
            ->orderBy('est.name', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    // qbByEstablishment déplacé dans GroupRepository (filtre groupes par établissement)
    
    // saveEstablishmentByName non utilisé, à ajouter via dashboard admin si besoin
    // public function saveEstablishmentByName(String $establishmentName): void
    // {
    //     $establishment = new Establishment();
    //     $establishment->setNameEstablishment($establishmentName);
    //     $this->em->persist($establishment);
    //     $this->em->flush();
    // }
}

<?php

namespace App\Repository;

use App\Entity\UserSphereRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

// enrichir si besoin (findByUser, findTopSpheresByUser…)
/** @extends ServiceEntityRepository<UserSphereRating> */
class UserSphereRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSphereRating::class);
    }
}

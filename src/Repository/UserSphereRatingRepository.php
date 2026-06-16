<?php

namespace App\Repository;

use App\Entity\User;
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

    /** @return int[] */
    public function findTopSphereIdsByUser(User $user, int $limit = 3, string $order = 'ASC'): array
    {
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

        return array_map(
            'intval',
            $this->createQueryBuilder('r')
                ->select('IDENTITY(r.sphere)')
                ->where('r.user = :user')
                ->setParameter('user', $user)
                ->orderBy('r.rating', $order)
                ->setMaxResults($limit)
                ->getQuery()
                ->getSingleColumnResult()
        );
    }
}

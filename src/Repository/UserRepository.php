<?php

namespace App\Repository;

use App\DTO\UserDTO;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUserWithGroupAndAuthority(int $id): ?User
    {
        return $this->createQueryBuilder('u')
            ->addSelect('c', 'a')
            ->join('u.group_id', 'c')
            ->join('u.authority_id', 'a')
            ->leftJoin('u.validations', 'v')
            ->leftJoin('v.activity', 'act')
            ->leftJoin('act.category', 'cat')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function insertStudent(User $user): User
    {
        $em = $this->getEntityManager();

        $em->persist($user);
        $em->flush();

        if ($user->getId() === null) {
            throw new RuntimeException('L\'insertion utilisateur n\'a pas pu être effectuée');
        }
        return $user;
    }

    public function findUserStats(int $userId): ?UserDTO
    {
        return $this->createQueryBuilder('u')
            ->select(
                'NEW App\DTO\UserDTO(
                u,
                COALESCE(SUM(cat.nbrPoint), 0)
            )'
            )
            ->join('u.group_id', 'c')
            ->join('u.authority_id', 'a')
            ->leftJoin('u.validations', 'v')
            ->leftJoin('v.activity', 'act')
            ->leftJoin('act.category', 'cat')
            ->where('u.idUser = :id')
            ->setParameter('id', $userId)
            ->groupBy('u.idUser')
            ->getQuery()
            ->getOneOrNullResult();
    }
}

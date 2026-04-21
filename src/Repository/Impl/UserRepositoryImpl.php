<?php

namespace App\Repository\Impl;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

class UserRepositoryImpl extends ServiceEntityRepository implements UserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUserWithGroupAndAuthority(int $id): ?User
    {
        return $this->createQueryBuilder('u')
            ->addSelect('c', 'a')
            ->join('u.group', 'c')
            ->join('u.authority', 'a')
            // 1. On remplace 'validations' par 'scans' (Le nom de la propriété dans User)
            ->leftJoin('u.scans', 's') 
            // 2. Je suppose que dans ton entité Scan, la relation s'appelle $activity (et non $name_activity)
            ->leftJoin('s.activity', 'act')
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
                COALESCE(SUM(cat.nbrPoints), 0),
                0
            )'
            )
            ->join('u.group', 'c')
            ->join('u.authority', 'a')
            ->leftJoin('u.scans', 's') // Même correction ici
            ->leftJoin('s.activity', 'act') // Même correction ici
            ->leftJoin('act.category', 'cat')
            // 3. Dans l'entité User, la propriété s'appelle $id et non $idUser
            ->where('u.id = :id') 
            ->setParameter('id', $userId)
            // 4. Même chose pour le groupBy
            ->groupBy('u.id') 
            ->getQuery()
            ->getOneOrNullResult();
    }
}
<?php

namespace App\Repository\Impl;

use App\DTO\UserDTO;
use App\Entity\Authority;
use App\Entity\Classroom;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDO;
use App\Repository\UserRepository;
use RuntimeException;

require_once __DIR__ . '/../../../vendor/autoload.php';

class UserRepositoryImpl extends ServiceEntityRepository implements UserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUserWithClassAndAuthority(int $id): ?User
    {
        return $this->createQueryBuilder('u')
            ->addSelect('c', 'a')
            ->join('u.class', 'c')
            ->join('u.authority', 'a')
            ->leftJoin('u.validations', 'v')
            ->leftJoin('v.activity', 'act')
            ->leftJoin('act.category', 'cat')
            ->where('u.idUser = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function insertStudent(User $user): User
    {
        $em = $this->getEntityManager();

        $em->persist($user);
        $em->flush();

        if ($user->getIdUser() === null) {
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
            ->join('u.classRoom', 'c')
            ->join('u.authority', 'a')
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

<?php

namespace App\Repository;

use App\DTO\UserDTO;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUserWithGroupAndAuthority(int $id): ?User
    {
        return $this->createQueryBuilder('u')
            ->addSelect('g', 'a')
            ->join('u.group', 'g')
            ->join('u.authority', 'a')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUserStats(int $userId): ?UserDTO
    {
        $result = $this->createQueryBuilder('u')
            ->select('u', 'g', 'e', 'COALESCE(SUM(cat.nbrPoints), 0) AS totalScore')
            ->leftJoin('u.group', 'g')
            ->leftJoin('g.establishment', 'e')
            ->leftJoin('u.scans', 's')
            ->leftJoin('s.activity', 'act')
            ->leftJoin('act.category', 'cat')
            ->where('u.id = :id')
            ->setParameter('id', $userId)
            ->groupBy('u.id', 'g.id', 'e.id')
            ->getQuery()
            ->getOneOrNullResult();

        if ($result === null) {
            return null;
        }

        $user = $result[0];

        return new UserDTO($user, (int) $result['totalScore'], 0);
    }

    // charge user par email/pseudo avec tous les JOIN pour AppUserProvider (1 requête/session)
    public function findByIdentifierEager(string $identifier): ?User
    {
        return $this->createQueryBuilder('u')
            ->addSelect('g', 'e', 'a', 'ar', 'r')
            ->leftJoin('u.group', 'g')
            ->leftJoin('g.establishment', 'e')
            ->leftJoin('u.authority', 'a')
            ->leftJoin('a.authorityRoles', 'ar')  // évite lazy load sur getRoles
            ->leftJoin('ar.role', 'r')
            ->where('u.email = :id OR u.pseudo = :id')
            ->setParameter('id', $identifier)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function insertStudent(User $user): User
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        if ($user->getId() === null) {
            throw new RuntimeException('L\'insertion utilisateur n\'a pas pu être effectuée.');
        }

        return $user;
    }

    // élèves d'un groupe avec leur score cumulé (pour accompagnateur ET à implémenter pour les élèves)
    /** @return array<int, array{id: int, pseudo: string, score: int, pokedAt: ?\DateTimeImmutable}> */
    public function findStudentScoresByGroupCode(string $groupCode): array
    {
        $rows = $this->createQueryBuilder('u')
            ->select('u.id, u.pseudo, u.pokedAt, COALESCE(SUM(cat.nbrPoints), 0) AS score')
            ->join('u.group', 'g')
            ->leftJoin('u.scans', 's')
            ->leftJoin('s.activity', 'act')
            ->leftJoin('act.category', 'cat')
            ->where('g.code = :code')
            ->setParameter('code', strtoupper(trim($groupCode)))
            ->groupBy('u.id')
            ->orderBy('score', 'DESC')
            ->getQuery()
            ->getScalarResult();

        return array_map(static fn(array $r) => [
            'id'      => (int) $r['id'],
            'pseudo'  => (string) $r['pseudo'],
            'score'   => (int) $r['score'],
            'pokedAt' => $r['pokedAt'] instanceof \DateTimeImmutable ? $r['pokedAt']->getTimestamp() : null,
        ], $rows);
    }

    public function findByPseudo(string $pseudo): ?User
    {
        return $this->findOneBy(['pseudo' => $pseudo]);
    }
}

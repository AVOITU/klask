<?php

namespace App\Service\Impl;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Entity\UserSphereRating;
use App\Repository\SphereRepository;
use App\Repository\UserRepository;
use App\Repository\UserSphereRatingRepository;
use App\Service\GroupService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[AsAlias]
class UserServiceImpl implements UserService
{
    public function __construct(
        private readonly UserRepository             $userRepository,
        private readonly GroupService               $groupService,
        private readonly SphereRepository           $sphereRepository,
        private readonly UserSphereRatingRepository $userSphereRatingRepository,
        private readonly EntityManagerInterface     $em,
    )
    {
    }

    public function insertStudent(User $student): User
    {
        return $this->userRepository->insertStudent($student);
    }

    public function findById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function createUserDTOById(int $idUser): ?UserDTO
    {
        $dto = $this->findUserStats($idUser);

        if ($dto === null) {
            return null;
        }

        $groupId = $dto->getUser()->getGroup()?->getId();

        return $groupId !== null
            ? $dto->withGroupTotalScore($this->groupService->findGroupTotalScore($groupId))
            : $dto;
    }

    private function findUserStats(int $userId): ?UserDTO
    {
        return $this->userRepository->findUserStats($userId);
    }

    public function getStudentScoresByGroupCode(string $groupCode): array
    {
        return $this->userRepository->findStudentScoresByGroupCode($groupCode);
    }

    public function pokeStudent(User $user): void
    {
        $user->setPokedAt(new \DateTimeImmutable());
        $this->em->flush();
    }

    public function clearPoke(User $user): void
    {
        $user->setPokedAt(null);
        $this->em->flush();
    }

    public function getTopSphereIds(User $user): array
    {
        return $this->userSphereRatingRepository->findTopSphereIdsByUser($user);
    }

    public function getBottomSphereIds(User $user): array
    {
        return $this->userSphereRatingRepository->findTopSphereIdsByUser($user, 3, 'DESC');
    }

    public function saveRatings(User $user, array $zoneRatings): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\UserSphereRating r WHERE r.user = :user')
            ->setParameter('user', $user)
            ->execute();

        foreach ($zoneRatings as $zoneName => $rating) {
            $sphere = $this->sphereRepository->findOneBy(['name' => $zoneName]);
            if ($sphere === null) {
                continue;
            }
            $this->em->persist(new UserSphereRating($user, $sphere, $rating));
        }

        $this->em->flush();
    }


    public function checkSessionTimeout(User $user, SessionInterface $session): bool
    {
        $roles = $user->getRoles();
        $maxIdleTime = null;

        if (in_array('ROLE_ADMIN', $roles, true)) {
            $maxIdleTime = 600;
        } elseif (in_array('ROLE_ACCOMPANYING', $roles, true)) {
            $maxIdleTime = 600;
        }

        if ($maxIdleTime === null) {
            return true; // Élève ou non concerné
        }

        $lastActivity = $session->get('klask_last_activity');
        if ($lastActivity !== null && (time() - $lastActivity) > $maxIdleTime) {
            return false; // Session expirée !
        }

        $session->set('klask_last_activity', time());
        return true;
    }
}

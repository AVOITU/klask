<?php

namespace App\Service\Impl;

use App\Service\StatisticsService;
use App\Entity\Scan;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class StatisticsServiceImpl implements StatisticsService
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function getGlobalStats(): array
    {
        return [
            'students_by_level'   => $this->getStudentsByLevel(),
            'top_activities'      => $this->getTopActivities(),
            'top_spheres'         => $this->getTopSpheres(),
            'internships_count'   => $this->getInternshipRequestsCount(),
            'group_ranking'       => $this->getGroupRanking(),
        ];
    }

    /**
     * Critère 1 : Nombre d'élèves inscrits, par niveau, par établissement
     */
    private function getStudentsByLevel(): array
    {
        return $this->em->createQueryBuilder()
            ->select('e.name as establishment_name, g.name as level_name, COUNT(u.id) as total_students')
            ->from(User::class, 'u')
            ->join('u.group', 'g')
            ->join('g.establishment', 'e')
            ->join('u.authority', 'a')
            ->where('a.authorityUser = :role')
            ->setParameter('role', 'ROLE_STUDENT')
            ->groupBy('e.id', 'g.id')
            ->orderBy('e.name', 'ASC')
            ->addOrderBy('g.name', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Critère 2.1 : Activités / stands les plus visités (Top 10)
     */
    private function getTopActivities(): array
    {
        return $this->em->createQueryBuilder()
            ->select('a.name as activity_name, COUNT(s.id) as scan_count')
            ->from(Scan::class, 's')
            ->join('s.activity', 'a')
            ->groupBy('a.id')
            ->orderBy('scan_count', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Critère 2.2 : Sphères les plus populaires
     */
    private function getTopSpheres(): array
    {
        return $this->em->createQueryBuilder()
            ->select('sp.name as sphere_name, sp.color, COUNT(s.id) as scan_count')
            ->from(Scan::class, 's')
            ->join('s.activity', 'a')
            ->join('a.sphere', 'sp')
            ->groupBy('sp.id')
            ->orderBy('scan_count', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Critère 3 : Nombre de demandes de stage/alternance
     */
    private function getInternshipRequestsCount(): int
    {
        return (int) $this->em->createQueryBuilder()
            ->select('COUNT(s.id)')
            ->from(Scan::class, 's')
            ->join('s.activity', 'a')
            // Assure-toi que isInternship existe bien dans ton entité Activity
            ->where('a.isInternship = true')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Critère 4 : Scores par groupe et classement général
     */
    private function getGroupRanking(): array
    {
        return $this->em->createQueryBuilder()
            ->select('g.code as group_code, g.name as level_name, e.name as establishment_name, SUM(c.nbrPoints) as total_score')
            ->from(Scan::class, 's')
            ->join('s.user', 'u')
            ->join('u.group', 'g')
            ->join('g.establishment', 'e')
            ->join('s.activity', 'a')
            ->join('a.category', 'c')
            ->groupBy('g.id')
            ->orderBy('total_score', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

}

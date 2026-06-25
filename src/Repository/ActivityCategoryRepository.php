<?php

namespace App\Repository;

use App\Entity\ActivityCategory;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ActivityCategoryRepository extends ServiceEntityRepository
{
// Ca pointait vers Event et ca bloquait pas mal de choses dans le CRUD
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityCategory::class);
    }
}

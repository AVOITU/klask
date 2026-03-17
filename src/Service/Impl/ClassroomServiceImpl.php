<?php

namespace App\Service\Impl;

use App\Entity\Classroom;
use App\Repository\ClassroomRepository;
use App\Service\ClassroomService;


require_once __DIR__ . '/../../../vendor/autoload.php';
class ClassroomServiceImpl implements ClassroomService
{
    public function __construct(
        private readonly ClassroomRepository $classeRepo
    ) {}

    public function findDistinctSchools(): array { return $this->classeRepo->findDistinctSchools(); }

    public function findById($classId) : ?Classroom{
        return $this->classeRepo->findById($classId);
    }

    public function findClassTotalScore($classId) :int {
        return $this->classeRepo->findClassTotalScore($classId);
    }
}

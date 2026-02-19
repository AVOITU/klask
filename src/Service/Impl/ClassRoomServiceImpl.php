<?php

namespace App\Service\Impl;

use App\Entity\ClassRoom;
use App\Repository\ClassRoomRepository;
use App\Service\ClassRoomService;


require_once __DIR__ . '/../../../vendor/autoload.php';
class ClassRoomServiceImpl implements ClassRoomService
{
    public function __construct(
        private ClassRoomRepository $classeRepo
    ) {}

    public function findAllSchools(): array { return $this->classeRepo->findAllSchools(); }

    public function findClassesBySchool($school): array { return $this->classeRepo->findClassesBySchool($school); }

    public function findById($classId) : ?ClassRoom{
        return $this->classeRepo->findById($classId);
    }
}

<?php

namespace Service\Impl;

use Model\ClassRoom;
use Repository\ClassRoomRepository;
use Service\ClassRoomService;

class ClassRoomServiceImpl implements ClassRoomService
{
    public function __construct(
        private ClassRoomRepository $classeRepo
    ) {}

    public function findAll(): array { return $this->classeRepo->findAll(); }

    public function findById($classId) : ?ClassRoom{
        return $this->classeRepo->findById($classId);
    }

    public function getAllSchoolsAndClasses(): array {
        $schoolsAndClassNames =$this->findAll();
        $schools = array_map(fn($classRoom) => $classRoom->getSchool(), $schoolsAndClassNames);
        $classes = array_map(fn($classRoom) => $classRoom->getNameClass(), $schoolsAndClassNames);
        return [$schools, $classes];
    }
}
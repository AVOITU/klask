<?php

namespace Service\Impl;

use Service\ScoresService;
use Service\ClassRoomService;

class ScoresServiceImpl implements ScoresService
{
    public function __construct(
        private ClassRoomService $classRoomService
        ) {}
        
    public function getScoresByClass(): array {
        return $this->classRoomService->getScoresByClass();
    }
}
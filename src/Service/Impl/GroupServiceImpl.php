<?php

namespace App\Service\Impl;

use App\Entity\Group;
use App\Repository\GroupRepository;
use App\Service\GroupService;


readonly class GroupServiceImpl implements GroupService
{
    public function __construct(
        private GroupRepository $groupRepository
    ) {}

    public function findById($groupId) : ?Group{
        return $this->groupRepository->findById($groupId);
    }

    public function findGroupTotalScore(int $groupId): int {
        return $this->groupRepository->findGroupTotalScore($groupId);
    }
}

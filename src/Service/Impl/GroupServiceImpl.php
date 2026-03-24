<?php

namespace App\Service\Impl;

use App\Entity\Group;
use App\Repository\GroupRepository;
use App\Service\GroupService;


class GroupServiceImpl implements GroupService
{
    public function __construct(
        private readonly GroupRepository $groupRepository
    ) {}

    public function findDistinctSchools(): array {
        return $this->groupRepository->findDistinctSchools();
    }

    public function findById($groupId) : ?Group{
        return $this->groupRepository->findById($groupId);
    }

    public function findClassTotalScore($groupId) :int {
        return $this->groupRepository->findClassTotalScore($groupId);
    }
}

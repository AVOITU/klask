<?php

namespace App\Service\Impl;

use App\Repository\GroupRepository;
use App\Service\GroupService;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
readonly class GroupServiceImpl implements GroupService
{
    public function __construct(private GroupRepository $groupRepository) {}

    public function findGroupTotalScore(int $groupId): int
    {
        return $this->groupRepository->findGroupTotalScore($groupId);
    }
}

<?php
namespace App\Tests\Service\Service;

use PHPUnit\Framework\TestCase;
use App\Entity\Group;
use App\Repository\GroupRepository;
use App\Service\Impl\GroupServiceImpl;

class GroupServiceTest extends TestCase
{
    public function testFindById(): void
    {
        $groupId = 1;
        $expectedGroup = new Group();
        $expectedGroup->setNameGroup('Test Group');

        $groupRepositoryMock = $this->createMock(GroupRepository::class);
        $groupRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($groupId)
            ->willReturn($expectedGroup);

        $groupService = new GroupServiceImpl($groupRepositoryMock);
        $result = $groupService->findById($groupId);

        $this->assertEquals($expectedGroup, $result);
    }
}

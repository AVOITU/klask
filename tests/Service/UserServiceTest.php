<?php
namespace App\Tests\Service\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\GroupService;
use App\Service\Impl\UserServiceImpl;
use PHPUnit\Framework\TestCase;
use App\DTO\UserDTO;

class UserServiceTest extends TestCase
{
    public function testInsertStudent(): void
    {
        $userRepoMock = $this->createMock(UserRepository::class);
        $groupServiceStub = $this->createStub(GroupService::class);
        $student = new User();

        $userRepoMock
            ->expects($this->once())
            ->method('insertStudent')
            ->with($student)
            ->willReturn($student);


        $userService = new UserServiceImpl($userRepoMock, $groupServiceStub);
        $result = $userService->insertStudent($student);
        $this->assertEquals($student, $result);
    }

    public function testFindUserWithGroupAndAuthority(): void
    {
        $userRepoMock = $this->createMock(UserRepository::class);
        $groupServiceStub = $this->createStub(GroupService::class);
        $user = new User();

        $userRepoMock
            ->expects($this->once())
            ->method('findUserWithGroupAndAuthority')
            ->with(1)
            ->willReturn($user);

        $userService = new UserServiceImpl($userRepoMock, $groupServiceStub);
        $result = $userService->findUserWithGroupAndAuthority(1);
        $this->assertEquals($user, $result);
    }

    public function testFindUserStats(): void
    {
        $userRepoMock = $this->createMock(UserRepository::class);
        $groupServiceStub = $this->createStub(GroupService::class);
        $userDTO = new UserDTO(new User(), 10, 20);
        $userRepoMock
            ->expects($this->once())
            ->method('findUserStats')
            ->with(1)
            ->willReturn($userDTO);

        $userService = new UserServiceImpl($userRepoMock, $groupServiceStub);
        $result = $userService->findUserStats(1);
        $this->assertEquals($userDTO, $result);
    }

    public function testFindUserStatsNull(): void
    {
        $userRepoMock = $this->createMock(UserRepository::class);
        $groupServiceStub = $this->createStub(GroupService::class);
        $userRepoMock
            ->expects($this->once())
            ->method('findUserStats')
            ->with(1)
            ->willReturn(null);

        $userService = new UserServiceImpl($userRepoMock, $groupServiceStub);
        $result = $userService->findUserStats(1);
        $this->assertNull($result);
    }

    public function testFindUserStatsEmpty(): void
    {
        $userRepoMock = $this->createMock(UserRepository::class);
        $groupServiceStub = $this->createStub(GroupService::class);
        $userDTO = new UserDTO(new User(), 0, 0);
        $userRepoMock
            ->expects($this->once())
            ->method('findUserStats')
            ->with(1)
            ->willReturn($userDTO);

        $userService = new UserServiceImpl($userRepoMock, $groupServiceStub);
        $result = $userService->findUserStats(1);
        $this->assertEquals($userDTO, $result);
    }

   /* public function testcreateUserDTObyId(): void
    {
        $userRepoMock = $this->createMock(UserRepository::class);
        $groupServiceMock = $this->createMock(GroupService::class);
        $user = new User();
        $userDTO = new UserDTO($user, 10, 20);

        $userRepoMock
            ->expects($this->once())
            ->method('findUserStats')
            ->with(1)
            ->willReturn($userDTO);

        $groupServiceMock
            ->expects($this->once())
            ->method('findGroupTotalScore')
            ->with($user->getGroup()->getId())
            ->willReturn(100);

        $userService = new UserServiceImpl($userRepoMock, $groupServiceMock);
        $result = $userService->createUserDTObyId(1);
        $expectedDTO = (new UserDTO($user, 10, 20))->withGroupTotalScore(100);
        $this->assertEquals($expectedDTO, $result);
    } */

}

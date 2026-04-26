<?php
namespace App\Tests\Service\Service;

use App\Security\RoleSecurity;
use PHPUnit\Framework\TestCase;
use App\Service\Impl\AuthorityServiceImpl;
use App\Repository\AuthorityRepository;
use App\Entity\Authority;
class AuthorityServiceTest extends TestCase
{
    public function testFindByRole(): void
    {
        $authorityRepositoryMock = $this->createMock(AuthorityRepository::class);
        $authorityService = new AuthorityServiceImpl($authorityRepositoryMock);

        $roleName = RoleSecurity::ADMIN->value;
        $expectedAuthority = new Authority();

        $authorityRepositoryMock
            ->expects($this->once())
            ->method('findByRole')
            ->with($roleName)
            ->willReturn($expectedAuthority);

        $result = $authorityService->findByRole($roleName);
        $this->assertSame($expectedAuthority, $result);
    }
}

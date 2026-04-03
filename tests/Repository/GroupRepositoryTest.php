<?php

namespace App\Tests\Repository;

use PHPUnit\Framework\TestCase;
use App\Repository\Impl\GroupRepositoryImpl;
use App\Entity\Group;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

class GroupRepositoryTest extends TestCase
{
    public function testQbByEstablishmentWithValue()
    {
        $qbMock = $this->createMock(QueryBuilder::class);

        // méthodes chaînées
        $qbMock->method('join')->willReturnSelf();
        $qbMock->method('orderBy')->willReturnSelf();

        // vérifications importantes
        $qbMock->expects($this->once())
            ->method('andWhere')
            ->with('e.name_establishment = :establishment')
            ->willReturnSelf();

        $qbMock->expects($this->once())
            ->method('setParameter')
            ->with('establishment', 'MySchool')
            ->willReturnSelf();

        // mock du repo
        $repo = $this->getMockBuilder(GroupRepositoryImpl::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createQueryBuilder'])
            ->getMock();

        $repo->expects($this->once())
            ->method('createQueryBuilder')
            ->with('g')
            ->willReturn($qbMock);

        $result = $repo->qbByEstablishment('MySchool');

        $this->assertSame($qbMock, $result);
    }

    public function testQbByEstablishmentWithoutValue()
    {
        $qbMock = $this->createMock(QueryBuilder::class);

        $qbMock->method('join')->willReturnSelf();
        $qbMock->method('orderBy')->willReturnSelf();

        $qbMock->expects($this->once())
            ->method('andWhere')
            ->with('1 = 0')
            ->willReturnSelf();

        $qbMock->expects($this->never())
            ->method('setParameter');

        $repo = $this->getMockBuilder(GroupRepositoryImpl::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createQueryBuilder'])
            ->getMock();

        $repo->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($qbMock);

        $result = $repo->qbByEstablishment(null);

        $this->assertSame($qbMock, $result);
    }

    public function testFindById()
    {
        $expected = new Group();

        // mock Query
        $queryMock = $this->createMock(Query::class);
        $queryMock->expects($this->once())
            ->method('getOneOrNullResult')
            ->willReturn($expected);

        // mock QueryBuilder
        $qbMock = $this->createMock(QueryBuilder::class);

        $qbMock->method('where')->willReturnSelf();
        $qbMock->method('setParameter')->willReturnSelf();

        $qbMock->expects($this->once())
            ->method('getQuery')
            ->willReturn($queryMock);

        // mock repo
        $repo = $this->getMockBuilder(GroupRepositoryImpl::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createQueryBuilder'])
            ->getMock();

        $repo->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($qbMock);

        $result = $repo->findById(1);

        $this->assertSame($expected, $result);
    }
}
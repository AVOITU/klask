<?php

namespace App\Tests\Repository;

use PHPUnit\Framework\TestCase;
use App\Repository\Impl\EstablishmentRepositoryImpl;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

class EstablishmentRepositoryImplTest extends TestCase
{
    public function testFindDistinctEstablishments()
    {
        // 1. Résultat attendu
        $expected = ['School A', 'School B'];

        // 2. Mock de la Query
        $queryMock = $this->createMock(Query::class);
        $queryMock->expects($this->once())
                  ->method('getSingleColumnResult')
                  ->willReturn($expected);

        // 3. Mock du QueryBuilder
        $qbMock = $this->createMock(QueryBuilder::class);

        $qbMock->method('select')->willReturnSelf();
        $qbMock->method('orderBy')->willReturnSelf();

        $qbMock->expects($this->once())
               ->method('getQuery')
               ->willReturn($queryMock);

        // 4. Mock du repository
        $repo = $this->getMockBuilder(EstablishmentRepositoryImpl::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createQueryBuilder'])
            ->getMock();

        $repo->expects($this->once())
             ->method('createQueryBuilder')
             ->with('est')
             ->willReturn($qbMock);

        // 5. Appel
        $result = $repo->findDistinctEstablishments();

        // 6. Vérification
        $this->assertEquals($expected, $result);
    }
}
<?php
namespace App\Tests\Service;

use App\Repository\EstablishmentRepository;
use App\Service\Impl\EstablishmentServiceImpl;
use App\Tests\Data\EstablishmentTestData;
use PHPUnit\Framework\TestCase;

class EstablishmentServiceTest extends TestCase
{
    public function testFindDistinctEstablishments(): void
    {
        $resultAssert = [EstablishmentTestData::ESTABLISHMENT_A,
                          EstablishmentTestData::ESTABLISHMENT_B];

        $establishmentRepositoryMock = $this->createMock(EstablishmentRepository::class);
        $establishmentRepositoryMock->expects($this->once())
            ->method(EstablishmentTestData::REPO_FIND_DISTINCT)
            ->willReturn($resultAssert);

        $establishmentService = new EstablishmentServiceImpl($establishmentRepositoryMock);
        $result = $establishmentService->findDistinctEstablishments();

        $this->assertEquals($result, $resultAssert);
    }
}

<?php
namespace App\Tests;

use App\Service\Impl\EstablishmentServiceImpl;
use App\Repository\EstablishmentRepository;
use PHPUnit\Framework\TestCase;

class EstablishmentServiceTest extends TestCase
{
    public function testFindDistinctEstablishments(): void
    {
        $establishmentRepositoryMock = $this->createMock(EstablishmentRepository::class);
        $establishmentRepositoryMock->expects($this->once())
            ->method('findDistinctEstablishments')
            ->willReturn(['Establishment A', 'Establishment B']);

        $establishmentService = new EstablishmentServiceImpl($establishmentRepositoryMock);
        $result = $establishmentService->findDistinctEstablishments();

        $this->assertEquals(['Establishment A', 'Establishment B'], $result);
    }
}
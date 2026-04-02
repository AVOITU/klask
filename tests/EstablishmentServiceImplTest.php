<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\Impl\EstablishmentServiceImpl;
use App\Repository\EstablishmentRepository;

class EstablishmentServiceImplTest extends TestCase
{
    public function testFindDistinctEstablishments()
    {
        // 1. Résultat attendu
        $expected = ['School A', 'School B'];

        // 2. Mock du repository avec "expects" pour lever la notice
        $repoMock = $this->createMock(EstablishmentRepository::class);
        $repoMock->expects($this->once())
                 ->method('findDistinctEstablishments')
                 ->willReturn($expected);

        // 3. Service avec le mock
        $service = new EstablishmentServiceImpl($repoMock);

        // 4. Appel
        $result = $service->findDistinctEstablishments();

        // 5. Vérification
        $this->assertEquals($expected, $result);
    }
}
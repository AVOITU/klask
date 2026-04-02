<?php

namespace App\Tests\Service;

use App\Entity\Authority;
use App\Entity\User;
use App\Repository\AuthorityRepository;
use App\Service\EstablishmentService;
use App\Service\Impl\InscriptionServiceImpl;
use App\Service\UserService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

// Import propre
// Import propre
// Import propre

#[CoversClass(InscriptionServiceImpl::class)]
class InscriptionServiceTest extends TestCase
{
    public function testRegisterStudentSuccess(): void
    {
        // 1. Mocks (On utilise les classes importées via 'use')
        $authRepoMock = $this->createMock(AuthorityRepository::class);
        $userServiceMock = $this->createMock(UserService::class);
        $establishmentServiceStub = $this->createStub(EstablishmentService::class);

        // 2. Configuration Authority
        $fakeAuthority = new Authority();
        $authRepoMock->expects($this->once())
            ->method('findByRole')
            ->with('STUDENT')
            ->willReturn($fakeAuthority);

        // 3. Configuration UserService
        $student = new User();
        $userServiceMock->expects($this->once())
            ->method('insertStudent')
            ->with($student)
            ->willReturn($student);

        // NOTE : On ne configure pas EstablishmentService car
        // registerStudent() ne l'appelle pas. PHPUnit accepte
        // un mock inutilisé SI on ne lui définit pas d'attentes (expects).

        // 4. Instanciation
        $inscriptionService = new InscriptionServiceImpl(
            $authRepoMock,
            $userServiceMock,
            $establishmentServiceStub
        );

        // 5. Exécution
        $result = $inscriptionService->registerStudent($student);

        // 6. Assertions
        $this->assertSame($student, $result);
        $this->assertSame($fakeAuthority, $result->getAuthority());
    }
}

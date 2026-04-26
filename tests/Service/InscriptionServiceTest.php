<?php
namespace App\Tests\Service\Service;

use App\Security\RoleSecurity;
use App\Service\UserService;
use App\Repository\AuthorityRepository;
use App\Service\Impl\InscriptionServiceImpl;
use App\Entity\Authority;
use App\Entity\User;
use App\Service\EstablishmentService;
use PHPUnit\Framework\TestCase;

class InscriptionServiceTest extends TestCase
{
    public function testRegisterStudentFailed(): void
    {
        $userServiceMock = $this->createMock(UserService::class);
        $authRepoMock = $this->createMock(AuthorityRepository::class);
        $estServiceStub = $this->createStub(EstablishmentService::class);
        $student = new User();
        $userServiceMock
            ->expects($this->never())
            ->method('insertStudent');

        $authRepoMock
            ->method('findByRole')
            ->with(RoleSecurity::STUDENT->value)
            ->willReturn(null);

        $inscriptionService = new InscriptionServiceImpl($authRepoMock, $userServiceMock, $estServiceStub);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Autorité ' . RoleSecurity::STUDENT->value . ' introuvable.');

        $inscriptionService->registerStudent($student);
    }
    public function testRegisterStudentSuccess(): void
    {
        $authRepoStub = $this->createStub(AuthorityRepository::class);
        $estServiceStub = $this->createStub(EstablishmentService::class);

        $userServiceMock = $this->createMock(UserService::class);

        $authority = new Authority();
        $student = new User();
        $authRepoStub->method('findByRole')->willReturn($authority);
        $userServiceMock
            ->expects($this->once())
            ->method('insertStudent')
            ->willReturn($student);

        $service = new InscriptionServiceImpl($authRepoStub, $userServiceMock, $estServiceStub);
        $result = $service->registerStudent($student);

        $this->assertSame($authority, $student->getAuthority());
        $this->assertSame($student, $result);
    }

    public function testingDistinctEstablishments(): void
    {
        $authRepoStub = $this->createStub(AuthorityRepository::class);
        $estServiceMock = $this->createMock(EstablishmentService::class);
        $userServiceStub = $this->createStub(UserService::class);

        $expected = ['Établissement A', 'Établissement B', 'Établissement C'];
        $estServiceMock
            ->expects($this->once())
            ->method('findDistinctEstablishments')
            ->willReturn($expected);

        $service = new InscriptionServiceImpl($authRepoStub, $userServiceStub, $estServiceMock);
        $result = $service->findDistinctEstablishments();
        $this->assertSame($expected, $result);
    }

    public function testGenerateDefaultNickname(): void
    {
        $authRepoStub = $this->createStub(AuthorityRepository::class);
        $userServiceStub = $this->createStub(UserService::class);
        $estServiceStub = $this->createStub(EstablishmentService::class);

        $service = new InscriptionServiceImpl($authRepoStub, $userServiceStub, $estServiceStub);
        $nickname = $service->generateDefaultNickname();
        $parts = explode(' ', $nickname, 2);
        $this->assertCount(2, $parts);
        $this->assertContains($parts[0], ['Dauphin', 'Goéland', 'Cormoran', 'Aigrette', 'Phoque', 'Hermine', 'Coccinelle', 'Ragondin', 'Chevreuil', 'Sanglier', 'Renard', 'Requin', 'Oursin', 'Crevette', 'Crabe', 'Mérou', 'Sauterelle', 'Escargot', 'Crapaud', 'Salamandre']);
        $this->assertContains($parts[1], ['du rêve', 'cosmique', 'magique', 'intrépide', 'cyber', 'casse-cou', 'chic', 'perplexe', 'à lunettes', 'gastronome', 'scolaire', 'globe-trotter', 'de la royauté', 'aquatique', 'musicos', 'excentrique', 'des îles', 'cool', 'aristocrate', 'héroïque']);
    }



}

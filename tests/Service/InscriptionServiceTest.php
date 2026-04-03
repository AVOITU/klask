<?php
namespace App\Tests;

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

        // On change l'attente : on s'attend à ce que "insertStudent" ne soit JAMAIS appelé
        $userServiceMock
            ->expects($this->never()) // <-- C'est ici le changement !
            ->method('insertStudent');

        $authRepoMock
            ->method('findByRole')
            ->with('STUDENT')
            ->willReturn(null);

        $inscriptionService = new InscriptionServiceImpl($authRepoMock, $userServiceMock, $estServiceStub);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Autorité STUDENT introuvable.');

        // Ici, le code va "crasher" comme prévu, donc les lignes après ne seront pas lues
        $inscriptionService->registerStudent($student);
    }
    public function testRegisterStudentSucces(): void
    {
        // On utilise createStub pour ce qui n'a pas besoin de vérification stricte
        $authRepoStub = $this->createStub(AuthorityRepository::class);
        $estServiceStub = $this->createStub(EstablishmentService::class);
    
        // On garde createMock pour UserService car on VEUT vérifier l'appel à insertStudent
        $userServiceMock = $this->createMock(UserService::class);

        $authority = new Authority(); // On instancie directement l'entité
        $student = new User();

        // Configuration du Stub (plus simple)
        $authRepoStub->method('findByRole')->willReturn($authority);

        // Configuration du Mock (vérification)
        $userServiceMock
            ->expects($this->once())
            ->method('insertStudent')
            ->willReturn($student);

        $service = new InscriptionServiceImpl($authRepoStub, $userServiceMock, $estServiceStub);
        $result = $service->registerStudent($student);

        $this->assertSame($authority, $student->getAuthority());
        $this->assertSame($student, $result);
    }

    public function testfindDistinctEstablishments(): void
    {
        $authRepoStub = $this->createStub(AuthorityRepository::class);
        $estServiceMock = $this->createMock(EstablishmentService::class);
        $userServiceStub = $this->createStub(UserService::class);

        $expected = ['Etablissement A', 'Etablissement B', 'Etablissement C'];
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

        // On vérifie que le nickname est une combinaison de l'animal et de l'adjectif
        $parts = explode(' ', $nickname);
        $this->assertCount(2, $parts);
        $this->assertContains($parts[0], ['Dauphin', 'Goéland', 'Cormoran', 'Aigrette', 'Phoque', 'Hermine', 'Coccinelle', 'Ragondin', 'Chevreuil', 'Sanglier', 'Renard', 'Requin', 'Oursin', 'Crevette', 'Crabe', 'Mérou', 'Sauterelle', 'Escargot', 'Crapaud', 'Salamandre']);
        $this->assertContains($parts[1], ['du rêve', 'cosmique', 'magique', 'intrépide', 'cyber', 'casse-cou', 'chic', 'perplexe', 'à lunettes', 'gastronome', 'scolaire', 'globe-trotter', 'de la royauté', 'aquatique', 'musicos', 'excentrique', 'des îles', 'cool', 'aristocrate', 'héroïque']);
    }

    
    
}
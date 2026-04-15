<?php
namespace App\Tests\Repository;

use App\Entity\Establishment;
use App\Repository\Impl\EstablishmentRepositoryImpl;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class EstablishmentRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?EstablishmentRepositoryImpl $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->repository = self::getContainer()->get(EstablishmentRepositoryImpl::class);
    }

    /**
     * Méthode Helper pour créer rapidement des établissements
     */
    private function createEstablishment(string $name): Establishment
    {
        $est = new Establishment();
        $est->setNameEstablishment($name);
        $this->entityManager->persist($est);
        
        return $est;
    }

    public function testFindDistinctEstablishmentsReturnsSortedAndUniqueNames(): void
    {
        $this->createEstablishment('Lycée Chaptal');
        $this->createEstablishment('Collège Brizeux');
        $this->createEstablishment('Lycée Chaptal');
        $this->createEstablishment('École Primaire Jean Macé');

        $this->entityManager->flush();

        $results = $this->repository->findDistinctEstablishments();

        $this->assertIsArray($results);
        
        $this->assertCount(3, $results, 'La méthode devrait filtrer les doublons.');

        $this->assertEquals('Collège Brizeux', $results[0]);
        $this->assertEquals('École Primaire Jean Macé', $results[1]);
        $this->assertEquals('Lycée Chaptal', $results[2]);
        
        $this->assertIsString($results[0]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
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
        // --- ARRANGE ---
        // On crée des données dans le désordre et AVEC un doublon
        $this->createEstablishment('Lycée Chaptal');
        $this->createEstablishment('Collège Brizeux');
        $this->createEstablishment('Lycée Chaptal'); // Le doublon !
        $this->createEstablishment('École Primaire Jean Macé');

        $this->entityManager->flush();

        // --- ACT ---
        $results = $this->repository->findDistinctEstablishments();

        // --- ASSERT ---
        $this->assertIsArray($results);
        
        // 1. Test du DISTINCT (On a inséré 4 lignes, on n'en attend que 3)
        $this->assertCount(3, $results, 'La méthode devrait filtrer les doublons.');

        // 2. Test de l'ordre alphabétique (C > E > L)
        $this->assertEquals('Collège Brizeux', $results[0]);
        $this->assertEquals('École Primaire Jean Macé', $results[1]);
        $this->assertEquals('Lycée Chaptal', $results[2]);
        
        // 3. Vérification du getSingleColumnResult (c'est bien une string, pas un objet)
        $this->assertIsString($results[0]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
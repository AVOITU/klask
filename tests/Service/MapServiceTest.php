<?php

namespace App\Tests\Service;

use App\Entity\Activity;
use App\Entity\Sphere;
use App\Repository\Impl\SphereRepositoryImpl;
use App\Service\Impl\MapServiceImpl;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class MapServiceTest extends TestCase
{
    private MapServiceImpl $mapService;
    
    // On type explicitement
    private SphereRepositoryImpl $sphereRepositoryStub;

    protected function setUp(): void
    {
        // On crée un pur Stub pour le Repository
        $this->sphereRepositoryStub = $this->createStub(SphereRepositoryImpl::class);

        // Instanciation de notre service avec le Stub
        $this->mapService = new MapServiceImpl($this->sphereRepositoryStub);
    }

    public function testGetPreparedSpheresCalculatesCorrectlyWithActivities(): void
    {
        // --- 1. ARRANGE (Préparation des données) ---

        // Remplacement par createConfiguredStub() pour éliminer les Notices
        $activityA = $this->createConfiguredStub(Activity::class, [
            'getPointXActivity' => 20.0,
            'getPointYActivity' => 30.0,
            'getDescriptionActivity' => 'Act A'
        ]);

        $activityB = $this->createConfiguredStub(Activity::class, [
            'getPointXActivity' => 60.0,
            'getPointYActivity' => 50.0,
            'getDescriptionActivity' => 'Act B'
        ]);

        $activitiesCollection = new ArrayCollection([$activityA, $activityB]);

        // Remplacement par createConfiguredStub()
        $sphereStub = $this->createConfiguredStub(Sphere::class, [
            'getId' => 1,
            'getNameSphere' => 'Sphère Test',
            'getColorSphere' => '#123456',
            'getActivities' => $activitiesCollection
        ]);

        // On configure notre Repository Stub pour qu'il renvoie notre fausse sphère
        $this->sphereRepositoryStub
            ->method('findAllWithActivities')
            ->willReturn([$sphereStub]);

        // --- 2. ACT (Exécution) ---
        
        $result = $this->mapService->getPreparedSpheres();

        // --- 3. ASSERT (Vérifications) ---
        
        $this->assertIsArray($result);
        $this->assertCount(1, $result);

        $preparedSphere = $result[0];

        // Vérifications classiques
        $this->assertEquals(1, $preparedSphere['id']);
        $this->assertEquals('Sphère Test', $preparedSphere['name']);
        $this->assertEquals('#123456', $preparedSphere['color']);

        // Vérifications des Mathématiques :
        // Centre X = (20 + 60) / 2 = 40
        $this->assertEquals(40.0, $preparedSphere['centerX']);
        
        // Centre Y = (30 + 50) / 2 = 40
        $this->assertEquals(40.0, $preparedSphere['centerY']);

        // Taille = max(40, (20 * 0.666)) + 8 = 48
        $this->assertEquals(48.0, $preparedSphere['size']);

        // Vérification que les activités ont bien été passées dans le tableau
        $this->assertCount(2, $preparedSphere['activities']);
        $this->assertEquals('Act A', $preparedSphere['activities'][0]['descriptionActivity']);
    }

    public function testGetPreparedSpheresHandlesEmptySphere(): void
    {
        // --- 1. ARRANGE ---
        // Remplacement par createConfiguredStub()
        $sphereStub = $this->createConfiguredStub(Sphere::class, [
            'getId' => 2,
            'getNameSphere' => 'Sphère Vide',
            'getColorSphere' => '#000000',
            'getActivities' => new ArrayCollection([]) 
        ]);

        $this->sphereRepositoryStub
            ->method('findAllWithActivities')
            ->willReturn([$sphereStub]);

        // --- 2. ACT ---
        $result = $this->mapService->getPreparedSpheres();

        // --- 3. ASSERT ---
        $this->assertCount(1, $result);
        $preparedSphere = $result[0];

        // Vérification des valeurs par défaut en cas d'absence d'activité
        $this->assertEquals(50.0, $preparedSphere['centerX'], 'Le centre X par défaut doit être 50');
        $this->assertEquals(50.0, $preparedSphere['centerY'], 'Le centre Y par défaut doit être 50');
        $this->assertEquals(10, $preparedSphere['size'], 'La taille par défaut doit être 10');
    }
}
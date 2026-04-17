<?php

namespace App\Tests\Repository\Impl;

use App\Entity\Sphere;
use App\Entity\Activity;
use App\Entity\ActivityCategory;
use App\Repository\Impl\SphereRepositoryImpl;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class SphereRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?SphereRepositoryImpl $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->repository = self::getContainer()->get(SphereRepositoryImpl::class);
    }

    /**
     * Méthode Helper pour créer rapidement des sphères
     */
    private function createSphere(string $name, string $color): Sphere
    {
        $sphere = new Sphere();
        $sphere->setNameSphere($name);
        $sphere->setColorSphere($color);
        $this->entityManager->persist($sphere);
        
        return $sphere;
    }

    /**
     * Méthode Helper pour créer rapidement des activités
     */
    private function createActivity(string $name, Sphere $sphere, ActivityCategory $category): Activity
    {
        $activity = new Activity();
        $activity->setNameActivity($name);
        $activity->setCategory($category);
        $activity->setSphere($sphere);
        $this->entityManager->persist($activity);
        
        return $activity;
    }

    public function testFindAllWithActivitiesReturnsSpheresAndLoadsActivities(): void
    {
        // 1. Préparation des données de test
        
        // Création d'une catégorie (obligatoire pour Activity)
        $category = new ActivityCategory();
        $category->setTypeCategory('Catégorie par défaut');
        $category->setNbrPoints(42);
        $category->setNbrMaxActivity(10000);
        $this->entityManager->persist($category);

        // Création des Sphères
        $sphereOrange = $this->createSphere('Sphère Orange', '#FFCC99');
        $sphereGrise = $this->createSphere('Sphère Grise', '#CCCCCC');

        // Création des Activités liées aux Sphères
        $this->createActivity('Stand A', $sphereOrange, $category);
        $this->createActivity('Stand B', $sphereOrange, $category);
        $this->createActivity('Stand C', $sphereGrise, $category);

        // On envoie tout en base de données
        $this->entityManager->flush();

        // ASTUCE CRUCIALE : On vide le cache de l'EntityManager.
        // Cela force Doctrine à refaire la requête SQL complète au lieu 
        // de nous renvoyer les objets qu'il a gardés en mémoire lors du flush().
        // Sans ça, on ne testerait pas vraiment notre 'leftJoin'.
        $this->entityManager->clear();

        // 2. Exécution de la méthode à tester
        $results = $this->repository->findAllWithActivities();

        // 3. Assertions
        $this->assertIsArray($results);
        $this->assertCount(2, $results, 'Il devrait y avoir 2 sphères en base.');

        // On vérifie le tri alphabétique (G avant O)
        $this->assertEquals('Sphère Grise', $results[0]->getNameSphere());
        $this->assertEquals('Sphère Orange', $results[1]->getNameSphere());

        // On vérifie que la jointure a bien récupéré les activités
        // La Sphère Grise devrait avoir 1 activité
        $this->assertCount(1, $results[0]->getActivities(), 'La sphère grise doit contenir 1 activité.');
        
        // La Sphère Orange devrait avoir 2 activités
        $this->assertCount(2, $results[1]->getActivities(), 'La sphère orange doit contenir 2 activités.');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
        $this->repository = null;
    }
}
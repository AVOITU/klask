<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\ActivityCategory;
use App\Entity\Sphere;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ActivityFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Création d'une catégorie par défaut pour satisfaire la contrainte (nullable: false)
        // À adapter selon ta structure actuelle pour les catégories d'activités
        $defaultCategory = new ActivityCategory();
        $defaultCategory->setTypeCategory('Catégorie par défaut');
        $defaultCategory->setNbrPoints(42);
        $defaultCategory->setNbrMaxActivity(10000);
        $manager->persist($defaultCategory);

        // --- Activités de la Sphère Orange ---
        $this->createActivity($manager, 'Stand A', 'Description du stand A (30 pts)', 45, 25, SphereFixtures::SPHERE_ORANGE, $defaultCategory);
        $this->createActivity($manager, 'Stand B', 'Description du stand B (15 pts)', 55, 28, SphereFixtures::SPHERE_ORANGE, $defaultCategory);
        $this->createActivity($manager, 'Stand C', 'Description du stand C (40 pts)', 50, 35, SphereFixtures::SPHERE_ORANGE, $defaultCategory);

        // --- Activités de la Sphère Grise ---
        $this->createActivity($manager, 'Stand D', 'Description du stand D (20 pts)', 20, 55, SphereFixtures::SPHERE_GREY, $defaultCategory);
        $this->createActivity($manager, 'Stand E', 'Description du stand E (10 pts)', 30, 65, SphereFixtures::SPHERE_GREY, $defaultCategory);

        // --- Activités de la Sphère Verte ---
        $this->createActivity($manager, 'Stand F', 'Description du stand F (50 pts)', 70, 55, SphereFixtures::SPHERE_GREEN, $defaultCategory);
        $this->createActivity($manager, 'Stand G', 'Description du stand G (25 pts)', 80, 65, SphereFixtures::SPHERE_GREEN, $defaultCategory);

        $manager->flush();
    }

    private function createActivity(ObjectManager $manager, string $name, string $description, float $x, float $y, string $sphereReference, ActivityCategory $category): void
    {
        $activity = new Activity();
        $activity->setNameActivity($name);
        $activity->setDescriptionActivity($description);
        $activity->setPointXActivity($x);
        $activity->setPointYActivity($y);
        $activity->setCategory($category);
        
        // CORRECTION ICI : Le nom de la référence en premier, et la classe en second
        $activity->setSphere($this->getReference($sphereReference, Sphere::class));

        $manager->persist($activity);
    }

    public function getDependencies(): array
    {
        // On indique à Doctrine qu'il doit impérativement charger SphereFixtures AVANT ActivityFixtures
        return [
            SphereFixtures::class,
        ];
    }
}
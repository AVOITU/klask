<?php

namespace App\DataFixtures;

use App\Entity\Sphere;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SphereFixtures extends Fixture
{
    public const SPHERE_ORANGE = 'sphere-orange';
    public const SPHERE_GREY = 'sphere-grey';
    public const SPHERE_GREEN = 'sphere-green';

    public function load(ObjectManager $manager): void
    {
        // Sphère Orange
        $sphereOrange = new Sphere();
        $sphereOrange->setNameSphere('Sphère Orange');
        $sphereOrange->setColorSphere('#FFCC99');
        $manager->persist($sphereOrange);
        $this->addReference(self::SPHERE_ORANGE, $sphereOrange);

        // Sphère Grise
        $sphereGrey = new Sphere();
        $sphereGrey->setNameSphere('Sphère Grise');
        $sphereGrey->setColorSphere('#CCCCCC');
        $manager->persist($sphereGrey);
        $this->addReference(self::SPHERE_GREY, $sphereGrey);

        // Sphère Verte
        $sphereGreen = new Sphere();
        $sphereGreen->setNameSphere('Sphère Verte');
        $sphereGreen->setColorSphere('#C5E0B4');
        $manager->persist($sphereGreen);
        $this->addReference(self::SPHERE_GREEN, $sphereGreen);

        $manager->flush();
    }
}
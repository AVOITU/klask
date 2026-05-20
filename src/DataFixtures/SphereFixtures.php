<?php

namespace App\DataFixtures;

use App\Entity\Sphere;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SphereFixtures extends Fixture
{
    /** @var array<string, array{color: string, x: float, y: float, r: float}> */
    private const SPHERES = [
        'CRÉATIF'     => ['color' => '#E74C3C', 'x' => 37.0, 'y' => 18.0, 'r' => 11.0],
        'RIGOUREUX'   => ['color' => '#3498DB', 'x' => 54.0, 'y' => 18.0, 'r' => 11.0],
        'NOUVEAUTÉ'   => ['color' => '#9B59B6', 'x' => 71.0, 'y' => 18.0, 'r' => 11.0],
        'EXTÉRIEUR'   => ['color' => '#27AE60', 'x' => 37.0, 'y' => 44.0, 'r' => 11.0],
        'COMMUNIQUER' => ['color' => '#F39C12', 'x' => 54.0, 'y' => 44.0, 'r' => 11.0],
        'UTILE'       => ['color' => '#1ABC9C', 'x' => 71.0, 'y' => 44.0, 'r' => 11.0],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SPHERES as $name => $data) {
            $sphere = new Sphere();
            $sphere->setName($name);
            $sphere->setColor($data['color']);
            $sphere->setPointX($data['x']);
            $sphere->setPointY($data['y']);
            $sphere->setRadius($data['r']);

            $manager->persist($sphere);
            $this->addReference('sphere_' . $name, $sphere);
        }

        $manager->flush();
    }
}

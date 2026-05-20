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
    /** @var list<array{string, string, string, float, float}> */
    private const ACTIVITIES = [
        // CRÉATIF
        ['Atelier Céramique',         'Modelage, poterie et créations manuelles.',           'CRÉATIF',     33.0, 14.0],
        ['Atelier Cuisine',           'Gastronomie créative et techniques culinaires.',       'CRÉATIF',     41.0, 22.0],
        // RIGOUREUX
        ['Stand Comptabilité',        'Présentation des métiers de la gestion et finances.',  'RIGOUREUX',   50.0, 14.0],
        ['Atelier Organisation',      'Méthodes de planification et gestion de projet.',      'RIGOUREUX',   58.0, 22.0],
        // NOUVEAUTÉ
        ['Stand Tech & IA',           'Intelligence artificielle et métiers du numérique.',   'NOUVEAUTÉ',   67.0, 14.0],
        ['Atelier Développement',     'Initiation au code et au développement web.',          'NOUVEAUTÉ',   75.0, 22.0],
        // EXTÉRIEUR
        ['Stand Nature & Éco',        'Métiers de l\'environnement et développement durable.','EXTÉRIEUR',   33.0, 40.0],
        ['Atelier Terrain',           'Agriculture, espaces verts et travaux extérieurs.',    'EXTÉRIEUR',   41.0, 48.0],
        // COMMUNIQUER
        ['Stand Ressources Humaines', 'Recrutement, management et relations humaines.',       'COMMUNIQUER', 50.0, 40.0],
        ['Atelier Communication',     'Marketing, médias et techniques de communication.',    'COMMUNIQUER', 58.0, 48.0],
        // UTILE
        ['Stand Soins',               'Métiers du soin, de la santé et du paramédical.',      'UTILE',       67.0, 40.0],
        ['Atelier Aide à la personne','Services à la personne et métiers du lien social.',    'UTILE',       75.0, 48.0],
    ];

    public function load(ObjectManager $manager): void
    {
        $category = $this->getReference(ActivityCategoryFixtures::CATEGORY_STAND_REFERENCE, ActivityCategory::class);

        foreach (self::ACTIVITIES as [$name, $description, $sphereName, $x, $y]) {
            $sphere = $this->getReference('sphere_' . $sphereName, Sphere::class);

            $activity = new Activity();
            $activity->setName($name);
            $activity->setDescription($description);
            $activity->setCategory($category);
            $activity->setSphere($sphere);
            $activity->setPointX($x);
            $activity->setPointY($y);

            $manager->persist($activity);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ActivityCategoryFixtures::class,
            SphereFixtures::class,
        ];
    }
}

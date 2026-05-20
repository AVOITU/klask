<?php

namespace App\DataFixtures;

use App\Entity\ActivityCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActivityCategoryFixtures extends Fixture
{
    public const CATEGORY_STAND_REFERENCE = 'category_stand';

    public function load(ObjectManager $manager): void
    {
        $stand = new ActivityCategory();
        $stand->setType('Stand');
        $stand->setNbrPoints(10);
        $stand->setNbrMaxActivity(3);

        $manager->persist($stand);
        $this->addReference(self::CATEGORY_STAND_REFERENCE, $stand);

        $manager->flush();
    }
}

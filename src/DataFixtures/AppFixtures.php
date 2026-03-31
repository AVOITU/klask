<?php

namespace App\DataFixtures;

use App\DataFixtures\AuthorityFixtures;
use App\DataFixtures\EstablishmentFixtures;
use App\DataFixtures\EventFixtures;
use App\DataFixtures\GroupFixtures;
use App\DataFixtures\RoleFixtures;
use App\DataFixtures\UserFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixtures::class,
            AuthorityFixtures::class,
            EstablishmentFixtures::class,
            EventFixtures::class,
            GroupFixtures::class,
            UserFixtures::class
        ];
    }
}
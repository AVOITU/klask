<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public const ROLE_REFERENCE = 'role';
    public function load(ObjectManager $manager): void
    {
        $role = new Role();
        $role->setNameRole("STUDENT");
        $this->addReference(self::ROLE_REFERENCE, $role);
        $manager->persist($role);
        $manager->flush();
    }
}
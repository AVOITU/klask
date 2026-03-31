<?php

namespace App\DataFixtures;

use App\Entity\Authority;
use App\Entity\Role;
use App\Entity\AuthorityRole; // Si c'est bien une entité de liaison
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorityFixtures extends Fixture
{
    public const AUTHORITY_REFERENCE = 'authority_student';

    public function load(ObjectManager $manager): void
    {
        $authority = new Authority();
        $authority->setAuthorityUser("STUDENT");
        $manager->persist($authority);

        
        $authorityRole = new AuthorityRole();
        $authorityRole->setAuthority($authority);
        $authorityRole->setRole($this->getReference(RoleFixtures::ROLE_REFERENCE, Role::class));
        $manager->persist($authorityRole);

        // On garde une référence pour plus tard si besoin
        $this->addReference(self::AUTHORITY_REFERENCE, $authority);

        $manager->flush();
    }
}
<?php

namespace App\DataFixtures;

use App\Entity\Authority;
use App\Entity\Group;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StudentFixtures extends Fixture implements DependentFixtureInterface
{
    // Crée des élèves de test dans le groupe GRP0002 (Première, 1er établissement)
    private const GROUP_REF = GroupFixtures::GROUP_REFERENCE . '_Première_0';

    private const STUDENTS = [
        'Renard vif',
        'Lapin gris',
        'Tigre calme',
        'Aigle fier',
        'Lynx agile',
    ];

    public function load(ObjectManager $manager): void
    {
        $group     = $this->getReference(self::GROUP_REF, Group::class);
        $authority = $this->getReference(AuthorityFixtures::AUTHORITY_STUDENT_REFERENCE, Authority::class);

        foreach (self::STUDENTS as $pseudo) {
            $user = new User();
            $user->setPseudo($pseudo)
                 ->setGroupCode('GRP0002')
                 ->setGroup($group)
                 ->setAuthority($authority)
                 ->setPassword('');

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AuthorityFixtures::class,
            GroupFixtures::class,
        ];
    }
}

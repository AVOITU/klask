<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Scan;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ScanFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findBy(['groupCode' => 'GRP0002']);
        $activities = $manager->getRepository(Activity::class)->findAll();

        if (empty($users) || empty($activities)) {
            return;
        }

        foreach ($users as $user) {
            if (in_array('ROLE_STUDENT', $user->getRoles(), true)) {
                $nbScans = rand(3, 6);
                $scannedActivitiesKeys = (array) array_rand($activities, $nbScans);

                foreach ($scannedActivitiesKeys as $index) {
                    $activity = $activities[$index];

                    // 1. On passe la date au constructeur du Scan
                    $scan = new Scan(new \DateTimeImmutable());

                    // 2. On associe l'utilisateur et l'activité via les setters
                    $scan->setUser($user);
                    $scan->setActivity($activity);

                    $manager->persist($scan);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            StudentFixtures::class,
            ActivityFixtures::class,
        ];
    }
}

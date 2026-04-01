<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Group;
use App\Service\InscriptionService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface; // AJOUTER CECI
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture implements DependentFixtureInterface // AJOUTER L'INTERFACE
{
    public function __construct(
        private readonly InscriptionService $inscriptionService
    ) {}

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 24; $i++) {
            if ($i < 12) {
                $group = $this->getReference(GroupFixtures::GROUP_REFERENCE.'_Seconde_'.$i, Group::class);
                for ($j = 0; $j < 10; $j++) {
                    $user = new User();
                    $user->setPseudoUser($this->inscriptionService->generateDefaultNickname());
                    $user->setGroup($group);
                    $this->inscriptionService->registerStudent($user);
                    $manager->persist($user);
                    }
                $group = $this->getReference(GroupFixtures::GROUP_REFERENCE.'_Première_'.$i, Group::class);
                for ($j = 0; $j < 10; $j++) {
                    $user = new User();
                    $user->setPseudoUser($this->inscriptionService->generateDefaultNickname());
                    $user->setGroup($group);
                    $this->inscriptionService->registerStudent($user);
                    $manager->persist($user);
                    }
            }
            else {
                $group = $this->getReference(GroupFixtures::GROUP_REFERENCE.'_Troisième_'.$i, Group::class);
                for ($j = 0; $j < 10; $j++) {
                    $user = new User();
                    $user->setPseudoUser($this->inscriptionService->generateDefaultNickname());
                    $user->setGroup($group);
                    $this->inscriptionService->registerStudent($user);
                    $manager->persist($user);
                    }
            }
        }
        
        $manager->flush();
    }

    // AJOUTER CETTE MÉTHODE
    public function getDependencies(): array
    {
        return [
            AuthorityFixtures::class,
            GroupFixtures::class,
        ];
    }
}
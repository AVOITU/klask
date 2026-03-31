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
        $group = $this->getReference(GroupFixtures::GROUP_REFERENCE, Group::class);

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setPseudoUser($this->inscriptionService->generateDefaultNickname());
            $user->setGroup($group);
            $user->setPassword("test"); // Ajoute un password si nécessaire

            // C'est ici que l'erreur est levée car le service fait une requête en base
            $this->inscriptionService->registerStudent($user);
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
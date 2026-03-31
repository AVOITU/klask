<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Group;
use App\Entity\Establishment;
use App\Entity\Event;

class GroupFixtures extends Fixture
{
    public const GROUP_REFERENCE = 'group';

    public function load(ObjectManager $manager): void
    {
       $group = new Group();
       $group->setNameGroup("Groupe 1");
       $group->setEvent($this->getReference(EventFixtures::EVENT_REFERENCE, Event::class));
    
       // On récupère spécifiquement 'establishment_0'
       $group->setEstablishment($this->getReference(EstablishmentFixtures::ESTABLISHMENT_REFERENCE . '_0', Establishment::class));
       $this->addReference(self::GROUP_REFERENCE, $group);
       $manager->persist($group);
       $manager->flush();
    }
}
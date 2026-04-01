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
        for( $i = 0; $i < 24; $i++ ) {
            if ( $i < 12 ) {
                $group = new Group();
                $group->setNameGroup("Seconde");
                $group->setEvent($this->getReference(EventFixtures::EVENT_REFERENCE, Event::class));
                $group->setEstablishment($this->getReference(EstablishmentFixtures::ESTABLISHMENT_REFERENCE . '_'.$i, Establishment::class));
                $this->addReference(self::GROUP_REFERENCE .'_Seconde_'.$i, $group);
                $manager->persist($group);
                $group = new Group();
                $group->setNameGroup("Première");
                $group->setEvent($this->getReference(EventFixtures::EVENT_REFERENCE, Event::class));
                $group->setEstablishment($this->getReference(EstablishmentFixtures::ESTABLISHMENT_REFERENCE . '_'.$i, Establishment::class));
                $this->addReference(self::GROUP_REFERENCE .'_Première_'.$i, $group);
                $manager->persist($group);
            }

              else {
                $group = new Group();
                $group->setNameGroup("Troisième");
                $group->setEstablishment($this->getReference(EstablishmentFixtures::ESTABLISHMENT_REFERENCE . '_'.$i, Establishment::class));
                $group->setEvent($this->getReference(EventFixtures::EVENT_REFERENCE, Event::class));
                $this->addReference(self::GROUP_REFERENCE .'_Troisième_'.$i, $group);
                $manager->persist($group);
            }
        }

       $manager->flush();
    }
}
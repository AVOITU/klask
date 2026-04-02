<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Event;

class EventFixtures extends Fixture
{
    public const EVENT_REFERENCE = 'event';

    public function load(ObjectManager $manager): void
    {   
        $event = new Event();
        $event->setNameEvent("Event test"); 
        $manager->persist($event);
        $this->addReference(self::EVENT_REFERENCE, $event);
        $manager->flush();
    }
}
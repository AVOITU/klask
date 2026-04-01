<?php

namespace App\DataFixtures;

use App\Entity\Establishment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EstablishmentFixtures extends Fixture
{
    public const ESTABLISHMENT_REFERENCE = 'establishment';
    public function load(ObjectManager $manager): void
    {
        $establishments = ["Lycée Jean-Marie Le Bris - Douarnenez", "Lycée Saint-Joseph - Concarneau", 
        "Lycée Pierre Guéguin - Concarneau", "Lycée Saint-Gabriel - Pont-l'Abbé", "Lycée Laennec - Pont-l'Abbé",
         "Lycée Le Paraclet - Quimper", "Lycée Sainte-Thérèse - Quimper", "Lycée Le Likès - Quimper",
          "Lycée Yves Thépot - Quimper", "Lycée de Cornouaille - Quimper", "Lycée Brizeux - Quimper", 
          "Lycée Chaptal - Quimper", "Collège Saint-Blaise - Douarnenez", "Collège des Sables Blancs - Concarneau", 
          "Collège Saint-Joseph - Fouesnant", "Collège de Kervihan - Fouesnant", "Collège Laennec - Pont-l'Abbé", 
          "Collège Diwan - Quimper", "Collège Sainte-Thérèse - Quimper", "Collège Saint-Jean Baptiste - Quimper", 
          "Collège Saint-Yves - Quimper", "Collège Brizeux - Quimper", "Collège Max Jacob - Quimper", 
          "Collège La Tour d'Auvergne - Quimper"];
        foreach ($establishments as $index => $name) {
           $establishment = new Establishment();
           $establishment->setNameEstablishment($name);
           $manager->persist($establishment);
        
           // On crée une référence unique : 'establishment_0', 'establishment_1', etc.
           $this->addReference(self::ESTABLISHMENT_REFERENCE . '_' . $index, $establishment);
       }
    
    $manager->flush();
        
    }
}
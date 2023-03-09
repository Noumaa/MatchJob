<?php

namespace App\DataFixtures;

use App\Entity\DemandStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DemandStatusFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $OfferStatus = array("En attente", "Rejeté", "Accepté");
        for($i = 0 ; $i<sizeof($OfferStatus) ; $i++)
        {
            $DemandStatus = new DemandStatus();
            $DemandStatus->setLabel($OfferStatus[$i]);
            $manager->persist($DemandStatus);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}

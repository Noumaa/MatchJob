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
        $statuses = \App\Service\Applications\ApplicationStatus::cases();

        foreach ($statuses as $status)
        {
            $DemandStatus = new DemandStatus();
            $DemandStatus->setLabel($status->value);
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

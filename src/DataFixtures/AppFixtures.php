<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\Offer;

class AppFixtures extends Fixture
{

    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("tata@gmail.com");
        $password = $this->hasher->hashPassword($user, 'tatatata');
        $user->setPassword($password);
        $user->setAddress("5 rue de la Fayette");
        $user->setZipCode("28000");
        $user->setCity("Chartres");
        $user->setCountry("France");
        $user->setPhone(("0610101010"));
        $manager->persist($user);
        $manager->flush();

        for($i=0;$i<20;$i++)
        {
            $offer = new Offer();
            $offer->setLabel("Stage de développeur n°". $i);
            $offer->setMoneyPerHour(12000);
            $offer->setDescription("Stage PHP ; C# ; JS ; PYTHON");
            $offer->setDuration(new \DateInterval('P2Y4DT6H8M'));
            $offer->setUser($user);
            $manager->persist($offer);
        }
        $manager->flush();
        
    }
}

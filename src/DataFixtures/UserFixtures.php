<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{

    //public const USERS_REFERENCE = 'users';
    private $hasher;
    public static $users = [];

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    /**
     * Cette fonction s'occupe de charger le jeu de données utilisateurs initial.
     * Elle contient une matrice @var persons contenant le nom et prénom des particuliers.
     * Un tableau @var businesses quand à lui contient le nom des sociétés.
     * 
     * L'**adresse mail** des particuliers utilise ce paterne `<prénom>.<nom>@gmail.com`, tandis que les sociétés ont ce format `contact@<nom>.fr`
     * 
     * Les **mots de passes** des comptes sont les prénoms et noms d'entreprise correspondants.
     * 
     * @param ObjectManager $manager - an instance of the ObjectManager that manages entities and manages the database
     */
    public function load(ObjectManager $manager): void
    {
        $persons = [
            ["nouma", "vallée"],
            ["felix", "arthur"],
            ["rayan", "nanas"],
            ["valentin", "bueno"],
            ["rose", "lafleur"],
            ["arthur", "petit"],
        ];

        $businesses = [
            "achille",
            "gfptech",
            "ikea",
            "mcdonalds",
            "intermarche",
        ];

        for ($i = 0; $i < sizeof($persons); $i++) {
            // Creating persons accounts
            $user = new User();

            $user->setEmail($persons[$i][0].".".$persons[$i][1]."@gmail.com");
            $user->setAddress("5 rue de la Fayette");
            $user->setZipCode("28000");
            $user->setCity("Chartres");
            $user->setCountry("France");
            $user->setPhone(("0610101010"));
            $user->setDateOfBirth(new \DateTime());
            $user->setFirstName(ucfirst($persons[$i][0]));
            $user->setLastName(ucfirst($persons[$i][1]));

            $password = $this->hasher->hashPassword($user, $persons[$i][0]);
            $user->setPassword($password);

            $user->addRole("ROLE_PERSON");

            $manager->persist($user);
        }

        for ($i = 0; $i < sizeof($businesses); $i++) {
            // Creating businesses accounts
            $user = new User();

            $user->setEmail("contact@".$businesses[$i].".fr");
            $user->setAddress("17 rue Z.A. Le Vallier");
            $user->setZipCode("28000");
            $user->setCity("Chartres");
            $user->setCountry("France");
            $user->setPhone(("0610101010"));
            $user->setSiret("DSQDSQDQSDFQ48565456");
            $user->setName(ucfirst($businesses[$i]));

            $password = $this->hasher->hashPassword($user, $businesses[$i]);
            $user->setPassword($password);

            $user->addRole("ROLE_BUSINESS"); 
            self::$users[] = $user;
            $manager->persist($user);
        }
        //$this->addReference(self::USERS_REFERENCE, $this->users);
        $manager->flush();
    }
    
}

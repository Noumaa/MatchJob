<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $hasher;
    public static $users = [];

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * Cette fonction s'occupe de charger le jeu de données utilisateurs initial.
     * Elle contient une matrice persons contenant le nom et prénom des particuliers.
     * Un tableau businesses quand à lui contient le nom des sociétés.
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
            ["nouma", "vallee"],
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

        // Creating persons accounts
        for ($i = 0; $i < sizeof($persons); $i++) {
            $user = new User();

            $user->setEmail($persons[$i][0].".".$persons[$i][1]."@gmail.com");
            $user->setAddress("5 rue de la Fayette");
            $user->setZipCode("28000");
            $user->setCity("Chartres");
            $user->setRegion("Eure-et-Loir");
            $user->setDepartment("Centre-Val de Loire");
            $user->setCountry("France");
            $user->setPhone(("0610101010"));
            $user->setDateOfBirth(new \DateTime());
            $user->setFirstName(ucfirst($persons[$i][0]));
            $user->setLastName(ucfirst($persons[$i][1]));

            $password = $this->hasher->hashPassword($user, $persons[$i][0]);
            $user->setPassword($password);

            $user->setRoles(["ROLE_PERSON"]);

            if ($persons[$i][0] == "nouma" || $persons[$i][0] == "felix") $user->setRoles(["ROLE_PERSON", "ROLE_ADMIN"]);

            $manager->persist($user);
        }

        // Creating businesses accounts
        for ($i = 0; $i < sizeof($businesses); $i++) {
            $user = new User();

            $user->setEmail("contact@".$businesses[$i].".fr");
            $user->setAddress("17 rue Z.A. Le Vallier");
            $user->setZipCode("28000");
            $user->setCity("Chartres");
            $user->setRegion("Eure-et-Loir");
            $user->setDepartment("Centre-Val de Loire");
            $user->setCountry("France");
            $user->setPhone(("0610101010"));
            $user->setSiret("DSQDSQDQSDFQ48565456");
            $user->setName(ucfirst($businesses[$i]));

            $password = $this->hasher->hashPassword($user, $businesses[$i]);
            $user->setPassword($password);

            $user->setRoles(["ROLE_BUSINESS"]);

            self::$users[] = $user;
            $manager->persist($user);
        }
        $manager->flush();
    }
    
}
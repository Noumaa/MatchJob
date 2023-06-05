<?php

namespace App\DataFixtures;

use App\Entity\Offer;
use App\Entity\User;
use App\DataFixtures\UserFixtures;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    

    public function load(ObjectManager $manager): void
    {

        $categories = 
        [
            ["Employé", "employe.png"],
            ["Informatique", "informatique.png"],
            ["PHP", "php.png"]
    ];
        

        for($i = 0 ; $i<sizeof($categories) ; $i++)
        {
            $category = new Category();
            $category->setLabel($categories[$i][0]);
            $category->setImageName($categories[$i][1]);
            
            
            $manager->persist($category);
            $manager->flush();
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            DemandStatusFixtures::class,
        ];
    }

    
}

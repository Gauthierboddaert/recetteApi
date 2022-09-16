<?php

namespace App\DataFixtures;

use App\Entity\Recette;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 0;$i<=3;$i++){
            $category = new Category();
            $category->setType("category .$i");
            $manager->persist($category);
        }
         
        for($i=0;$i<10; $i++){
            $recette = new Recette();
            $recette->setTitle("mon titre .$i");
            $recette->setDescription("ma description .$i");
            
            $manager->persist($recette);
        };

        $manager->flush();
    }
}

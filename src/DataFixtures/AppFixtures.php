<?php

namespace App\DataFixtures;

use App\Entity\Recette;
use App\Entity\Category;
use App\Entity\User;
use App\Enum\EnumCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $user = new User();
        $user->setEmail("Boddaert.Gauthier@gmail.com");
        $user->setPassword("test");
        $user->setUsername('Gauthier');
        for($i = 0;$i<=3;$i++){
            $category = new Category();
            $category->setType(EnumCategory::VIANDE);
            $manager->persist($category);
        }
         
        for($i=0;$i<10; $i++){
            $recette = new Recette();
            $recette->setTitle("mon titre .$i");
            $recette->setDescription("ma description .$i");
            $recette->setUser($user);
            $recette->setCategoryPlat($category);
            $manager->persist($recette);
        };

        $manager->flush();
    }
}

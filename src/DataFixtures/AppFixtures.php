<?php

namespace App\DataFixtures;

use App\Entity\Recette;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\User;
use App\Enum\EnumCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{

    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr-FR");

        $user = new User();
        $user->setRoles(['IS_AUTHENTICATED_ANONYMOUSLY']);
        $user->setEmail("user@gmail.com");
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "coucou"));
        $user->setUsername($faker->name());
        $manager->persist($user);

        $admin = new User();
        $admin->setRoles(['IS_AUTHENTICATED_FULLY']);
        $admin->setEmail("admin@gmail.com");
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, "coucou"));
        $admin->setUsername($faker->name());
        
        for($i = 0;$i<=3;$i++){
            $category = new Category();
            $category->setType(EnumCategory::VIANDE);
            $manager->persist($category);
        }
         
        for($i=0;$i<10; $i++){
            $recette = new Recette();
            $recette->setTitle($faker->text(10));
            $recette->setDescription($faker->text(100));
            $recette->setUser($admin);
            $recette->setCategoryPlat($category);
            $image = new Image();
            $image->setPath("C:\\Users\\BODDAERTGauthier\\Desktop\\recetteApi\\public\\upload\\ham.jpg");
            $recette->addImage($image);
           
            $manager->persist($recette);
        };

        $manager->flush();
    }
}

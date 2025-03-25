<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // for ($i = 0; $i < 10; $i++) {
        //     $category = new Category();
        //     $category->setNom("category" . $i);
        //     $manager->persist($category);
        // }

        $manager->flush();
    }
}

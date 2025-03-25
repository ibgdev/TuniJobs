<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Company;
use App\Entity\CompanyUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CompanyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $faker = Factory::create();

        // $users = []; // Create the users array before using it

        // // Create CompanyUser entities first
        // for ($i = 0; $i < 10; $i++) {
        //     $user = new CompanyUser();
        //     $user->setEmail($faker->email);
        //     $user->setNom($faker->name);
        //     $user->setRoles();
        //     $user->setPassword('password123');  // Ideally, password should be hashed
        //     $manager->persist($user);
        //     $users[] = $user;
        // }

        // // Now create Company entities and assign each company a responsable

        // for ($i = 0; $i < 10; $i++) {
        //     $company = new Company();
        //     $company->setNom("company" . $i);
        //     $company->setMatriculeFiscale($i * 253);
        //     $company->setAdresse("rue" . $i);
        //     $company->setCompanyUser($users[$i]); // Assign the responsible CompanyUser
        //     $company->setSecteur("secteur" . $i);
        //     $company->setSiteweb("site" . $i);
        //     $company->setTelephone("12345678");
        //     $company->setCompanyUser($users[$i]); // Associate the user with the company
        //     $manager->persist($company);
        // }

        // $manager->flush();
    }

}

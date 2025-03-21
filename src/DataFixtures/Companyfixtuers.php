<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;

class Companyfixtuers extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create users
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setNom($faker->name);
            $user->setRoles(['ROLE_ENTERPRISE']);
            $user->setPassword('password123');
            $user->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            $users[] = $user;
        }
        for ($i=0; $i < 10; $i++) { 
            $company = new Company();
            $company->setNom("company".$i);
            $company->setMatriculeFiscle($i*253);
            $company->setAdresse("rue".$i);
            $company->setResponsable($users[$i]);
            $company->setSecteur("secteur".$i);
            $company->setSiteweb("site".$i);
            $company->setStatuValidation(1);
            $company->setTelephone("12345678");
            $manager->persist($company);
        }

        $manager->flush();
    }
}

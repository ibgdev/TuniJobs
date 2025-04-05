<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\CompanyUser;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CompanyFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $users = []; // Create the users array before using it

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
        //     $manager->persist($company);
        // }

        // for ($i = 0; $i < 10; $i++) {
        //     $user = new User();
        //     $user->setEmail($faker->email);
        //     $user->setNom($faker->name);
        //     $user->setRoles(['ROLE_CANDIDATE']); // Il faut fournir un tableau vide ou des rôles définis
        //     $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        //     $user->setCreatedAt(new \DateTimeImmutable());  
        //     $manager->persist($user);
        //     $users[] = $user;
        // }

        // $manager->flush();
    }
}

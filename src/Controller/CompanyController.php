<?php

namespace App\Controller;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CompanyController extends AbstractController
{
    #[Route('/companies', name: 'company.all')]
    public function index( EntityManagerInterface $em): Response
    {
        $companies = $em->getRepository(Company::class)->findAll();

        return $this->render('company/index.html.twig', [
            'companies' => $companies,
        ]);
    }
}

<?php
namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/company/add', name: 'company.add')]
    public function add( EntityManagerInterface $em, Request $request): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Company added successfuly');
            $em->persist($company);
            $em->flush();
            return $this->redirectToRoute("company.all");
        }
        return $this->render('company/add.html.twig', [
            'form' => $form 
        ]);
    }
    #[Route('/company/edit/{id}', name: 'company.edit')]
    public function edit( EntityManagerInterface $em, Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Company added successfuly');
            $em->persist($company);
            $em->flush();
            return $this->redirectToRoute("company.all");
        }
        return $this->render('company/edit.html.twig', [
            'form' => $form 
        ]);
    }
    #[Route('/company/delete/{id}', name: 'company.delete')]
    public function delete( EntityManagerInterface $em, Company $company): Response
    {
        $em->remove($company);
        $em->flush();
        return $this->redirectToRoute('company.all');
    }
}
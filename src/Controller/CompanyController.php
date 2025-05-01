<?php
namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CompanyController extends AbstractController
{
    #[Route('/admin/companies', name: 'company.all')]
    public function index( EntityManagerInterface $em): Response
    {
        $companies = $em->getRepository(Company::class)->findAll();
        $users = $em->getRepository(User::class)->findUserHasNoCompany();

        return $this->render('company/index.html.twig', [
            'companies' => $companies,
            'users' => $users
        ]);
    }
    #[Route('/companies', name: 'cond.company.all')]
    public function companies( EntityManagerInterface $em): Response
    {
        $companies = $em->getRepository(Company::class)->findAll();

        return $this->render('condidate/companies.html.twig', [
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
            $em->persist($company);
            $this->addFlash('success', 'Company added successfuly');
            $em->flush();
            return $this->redirectToRoute("company.all");
        }
        return $this->render('company/add.html.twig', [
            'form' => $form 
        ]);
    }
    #[Route('/admin/company/edit/{id}', name: 'company.edit')]
    public function edit( EntityManagerInterface $em, Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Company updated successfuly');
            $em->persist($company);
            $em->flush();
            return $this->redirectToRoute("company.all");
        }
        return $this->render('company/edit.html.twig', [
            'form' => $form 
        ]);
    }
    #[Route('/admin/company/delete/{id}', name: 'company.delete')]
    public function delete( EntityManagerInterface $em, Company $company): Response
    {
        $this->addFlash('success', 'Company deleted successfuly');
        $em->remove($company);
        $em->flush();
        return $this->redirectToRoute('company.all');
    }
    
    #[Route('/admin/company/assign/{idCompany}', name: 'company.assign_manager', methods: ['POST'])]
    public function assignManager(EntityManagerInterface $em, Request $request, int $idCompany): Response
    {
        $company = $em->getRepository(Company::class)->find($idCompany);
    
        if (!$company) {
            throw $this->createNotFoundException('Company not found.');
        }
    
        $responsableId = $request->request->get('responsable');
        $responsable = $em->getRepository(User::class)->find($responsableId);
    
        if ($responsable) {
            $company->setResponsable($responsable);
            $responsable->setRoles(["ROLE_ENTERPRISE"]);
            $em->persist($company);
            $em->persist($responsable);
            $em->flush();
        }
    
        return $this->redirectToRoute('company.all');
    }
    
}
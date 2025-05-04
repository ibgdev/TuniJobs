<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\JobOffer;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CondidateController extends AbstractController
{
    #[Route('/companies/{id}', name: 'condidate.entreprise')]
    public function index(Company $company, EntityManagerInterface $em): Response
    {   
        $offers = $em->getRepository(JobOffer::class)->findByCompanyId($company->getId());
    
        $reviews = $em->getRepository(Review::class)->findBy([
            'entreprise' => $company
        ]);
    
        return $this->render('condidate/detailsEntreprise.html.twig', [
            'company' => $company,
            'offers' => $offers,
            'reviews' => $reviews
        ]);
    }
    
}

<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReviewController extends AbstractController
{
    #[Route('/companies/{id}/avis', name: 'app_review')]
    public function companyReview(Request $request, Company $company, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
    
        // Check if user is logged in
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
    
        // Check if user has already reviewed this company
        $existingReview = $em->getRepository(Review::class)->findOneBy([
            'entreprise' => $company,
            'utilisateur' => $user
        ]);
    
        $review = $existingReview ?? new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$existingReview) {
                $review->setEntreprise($company);
                $review->setUtilisateur($user);
                $review->setCreatedAt(new \DateTimeImmutable());
                $em->persist($review);
            }
            
            $em->flush();
            
            $this->addFlash('success', 'Votre avis a été enregistré avec succès!');
            return $this->redirectToRoute('condidate.entreprise', ['id' => $company->getId()]);
        }
    
        return $this->render('condidate/review.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
            'existingReview' => $existingReview
        ]);
    }
}

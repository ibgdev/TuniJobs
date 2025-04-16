<?php

namespace App\Controller;

use App\Entity\JobOffer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User; // or wherever your User class is
use App\Form\JobOfferType;
use Symfony\Component\HttpFoundation\Request;

final class JobOfferController extends AbstractController
{
    #[Route('/jobs', name: 'job.all')]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('job_offer/index.html.twig', [
            'controller_name' => 'JobOfferController',
        ]);
    }
    #[Route('/entreprise/jobs', name: 'job.entreprise')]
    public function entreprise_jobs(EntityManagerInterface $em): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $CompanyId = $user->getCompany()->getId();

        $jobOffers = $em->getRepository(JobOffer::class)->findByCompanyId($CompanyId);
        return $this->render('entreprise/jobs.html.twig', [
            'jobOffers' => $jobOffers,
        ]);
    }


    #[Route('/entreprise/jobs/add', name: 'job.entreprise.add')]
    public function entreprise_jobs_add(EntityManagerInterface $em, Request $request): Response
    {
        $jobOffer = new JobOffer();
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $company = $user->getCompany();
            $jobOffer->setEntreprise($company);
            $em->persist($jobOffer);
            $em->flush();

            return $this->redirectToRoute('job.entreprise');
        }
        return $this->render('entreprise/job_add.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/entreprise/jobs/edit/{id}', name: 'job.entreprise.edit')]
    public function entreprise_jobs_edit(EntityManagerInterface $em, JobOffer $jobOffer, Request $request): Response
    {
        $form = $this->createForm(JobOfferType::class, $jobOffer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($jobOffer);
            $em->flush();
            return $this->redirectToRoute('job.entreprise');
        }
        return $this->render('entreprise/jobs_edit.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/entreprise/jobs/delete/{id}', name: 'job.entreprise.delete')]
    public function entreprise_jobs_delete(EntityManagerInterface $em, JobOffer $jobOffer): Response
    {
        $em->remove($jobOffer);
        $em->flush();
        return $this->redirectToRoute('job.entreprise');
    }
}

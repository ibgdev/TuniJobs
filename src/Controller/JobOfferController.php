<?php

namespace App\Controller;

use App\Entity\Category;
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
        $jobOffers = $em->getRepository(JobOffer::class)->findAll();
        return $this->render('condidate/jobs.html.twig', [
            'joboffers' => $jobOffers,
            'locations' => $this->getTunisianGovernorates(),
            'categories' => $em->getRepository(Category::class)->findAll(),
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
            $this->addFlash('success', 'offre ajouté avec succée!');
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
            $this->addFlash('success', 'offre mise à jour avec succée!');
            $em->persist($jobOffer);
            $em->flush();
            return $this->redirectToRoute('job.entreprise');
        }
        return $this->render('entreprise/jobs_edit.html.twig', [
            'form' => $form,
            'jobOffer' => $jobOffer,
        ]);
    }
    #[Route('/entreprise/jobs/delete/{id}', name: 'job.entreprise.delete')]
    public function entreprise_jobs_delete(EntityManagerInterface $em, JobOffer $jobOffer): Response
    {
        $em->remove($jobOffer);
        $em->flush();
        return $this->redirectToRoute('job.entreprise');
    }

    public function getTunisianGovernorates(): array
    {
        $governorates = [
            'Ariana', 'Béja', 'Ben Arous', 'Bizerte', 'Gabès', 'Gafsa', 'Jendouba', 'Kairouan',
            'Kasserine', 'Kébili', 'Le Kef', 'Mahdia', 'La Manouba', 'Médenine', 'Monastir',
            'Nabeul', 'Sfax', 'Sidi Bouzid', 'Siliana', 'Sousse', 'Tataouine', 'Tozeur',
            'Tunis', 'Zaghouan'
        ];

        return array_combine($governorates, $governorates);
    }
}

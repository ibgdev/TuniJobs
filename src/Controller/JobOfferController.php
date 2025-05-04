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
    // jobs for condidatee 


    #[Route('/jobs', name: 'job.all', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $search = $request->query->get('search');
        $category = $request->query->get('category');
        $location = $request->query->get('location');

        $qb = $em->getRepository(JobOffer::class)->createQueryBuilder('j')
            ->leftJoin('j.categorie', 'c')
            ->addSelect('c');

        if ($search) {
            $qb->andWhere('LOWER(j.titre) LIKE :search')
                ->setParameter('search', '%' . strtolower($search) . '%');
        }

        if ($category) {
            $qb->andWhere('c.id = :category')
                ->setParameter('category', $category);
        }

        if ($location) {
            $qb->andWhere('j.location = :location')
                ->setParameter('location', $location);
        }

        $jobOffers = $qb->getQuery()->getResult();

        return $this->render('condidate/jobs.html.twig', [
            'joboffers' => $jobOffers,
            'locations' => $this->getTunisianGovernorates(),
            'categories' => $em->getRepository(Category::class)->findAll(),
        ]);
    }

    #[Route('/jobs/details/{id}', name: 'job.details')]
    public function details(EntityManagerInterface $em, JobOffer $jobOffer): Response
    {
        return $this->render('condidate/jobDetails.html.twig', [
            'joboffer' => $jobOffer
        ]);
    }



    // Jobs for entrepprise
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

    #[Route('/entreprise/jobs/preview/{id}', name: 'job.entreprise.preview')]
    public function details_entreprise(EntityManagerInterface $em, JobOffer $jobOffer): Response
    {
        return $this->render('entreprise/jobDetails.html.twig', [
            'joboffer' => $jobOffer
        ]);
    }

    public function getTunisianGovernorates(): array
    {
        $governorates = [
            'Ariana',
            'Béja',
            'Ben Arous',
            'Bizerte',
            'Gabès',
            'Gafsa',
            'Jendouba',
            'Kairouan',
            'Kasserine',
            'Kébili',
            'Le Kef',
            'Mahdia',
            'La Manouba',
            'Médenine',
            'Monastir',
            'Nabeul',
            'Sfax',
            'Sidi Bouzid',
            'Siliana',
            'Sousse',
            'Tataouine',
            'Tozeur',
            'Tunis',
            'Zaghouan'
        ];

        return array_combine($governorates, $governorates);
    }
}

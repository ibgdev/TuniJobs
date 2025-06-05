<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Company;
use App\Entity\JobOffer;
use App\Entity\User;
use App\Form\ApplicationType;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ApplicationController extends AbstractController
{

    #[Route('/applications', name: 'app_application.all')]
    public function get_apps(EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute("app_login");
        }
        $apps = $em->getRepository(Application::class)->findBy(["utilisateur" => $this->getUser()]);
        return $this->render("condidate/applications.html.twig", [
            "applications" => $apps
        ]);
    }
    #[Route('/entreprise/applications', name: 'entre_application.all')]
    public function entreprise_apps(EntityManagerInterface $em): Response
    {
        $entreprise = $em->getRepository(Company::class)->findBy(["responsable" => $this->getUser()]);

        if (!$this->getUser()) {
            return $this->redirectToRoute("app_login");
        } else {
            if (!in_array("ROLE_ENTERPRISE", $this->getUser()->getRoles())) {
                return $this->redirectToRoute("app_home");
            }
        }

        // Fetch all applications
        $apps = $em->getRepository(Application::class)->findByEntreprise($entreprise[0]->getId());

        // Calculate the total number of applications (all_count)
        $all_count = count($apps);

        // Calculate the number of pending applications (pending_count)
        $pending_count = count(array_filter($apps, function ($app) {
            return $app->getStatut() === 'en attente'; // Adjust 'pending' to the correct status
        }));

        $accepted_count = count(array_filter($apps, function ($app) {
            return $app->getStatut() === 'Acceptée'; // Adjust 'pending' to the correct status
        }));
        $rejected_count = count(array_filter($apps, function ($app) {
            return $app->getStatut() === 'Refusée';
        }));

        return $this->render("entreprise/applications.html.twig", [
            "applications" => $apps,
            "all_count" => $all_count, // Pass the all_count variable to the template
            "pending_count" => $pending_count,
            "accepted_count" => $accepted_count,
            "rejected_count" => $rejected_count
        ]);
    }



    #[Route('/entreprise/applications/filter', name: 'app_candidature_index')]
    public function entreprise_status_apps(Request $request, EntityManagerInterface $em): Response
    {
        $statut = $request->query->get('statut');  // Get the statut from the query parameter
        $entreprise = $em->getRepository(Company::class)->findBy(['responsable' => $this->getUser()]);

        if (!$this->getUser()) {
            return $this->redirectToRoute("app_login");
        } else {
            if (!in_array("ROLE_ENTERPRISE", $this->getUser()->getRoles())) {
                return $this->redirectToRoute("app_home");
            }
        }

        // Calculate the number of pending applications (pending_count)



        // Get all applications for the entreprise
        $apps = $em->getRepository(Application::class)->findByEntreprise($entreprise[0]->getId());
        $all_count = count($apps);

        $pending_count = count(array_filter($apps, function ($app) {
            return $app->getStatut() === 'en attente'; // Adjust 'pending' to the correct status
        }));

        $accepted_count = count(array_filter($apps, function ($app) {
            return $app->getStatut() === 'Acceptée'; // Adjust 'pending' to the correct status
        }));
        $rejected_count = count(array_filter($apps, function ($app) {
            return $app->getStatut() === 'Refusée'; // Adjust 'pending' to the correct status
        }));

        // If a statut is provided, filter the applications by status
        if ($statut) {
            $apps = array_filter($apps, function ($app) use ($statut) {
                return $app->getStatut() === $statut;  // Adjust 'getStatut()' to your actual method for getting status
            });
        }

        // Return the filtered applications to the Twig template
        return $this->render("entreprise/applications.html.twig", [
            "applications" => $apps,
            "all_count" => $all_count,
            "pending_count" => $pending_count,
            "accepted_count" => $accepted_count,
            "rejected_count" => $rejected_count
        ]);
    }

    #[Route('/jobs/details/{id}/apply', name: 'app_application')]
    public function index(Request $request, EntityManagerInterface $em, MailerService $mailerService, Security $security, JobOffer $jobOffer): Response
    {
        if (!($this->getUser() && in_array("ROLE_CANDIDATE", $this->getUser()->getRoles()))) {
            return $this->redirectToRoute('app_login');
        }

        $user = $security->getUser();
        if ($this->alreadyApplied($user, $jobOffer, $em)) {
            $this->addFlash('error', 'Vous avez déjà postulé à cette offre.');
            return $this->redirectToRoute('job.details', ['id' => $jobOffer->getId()]);
        }

        $application = new Application();
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file uploads
            $cvFile = $form->get('cv')->getData();
            $motivationFile = $form->get('letterMotivation')->getData();

            if ($cvFile) {
                $cvFilename = uniqid() . '.' . $cvFile->guessExtension();
                $cvFile->move($this->getParameter('cv_directory'), $cvFilename);
                $application->setCv($cvFilename);
            }

            if ($motivationFile) {
                $letterFilename = uniqid() . '.' . $motivationFile->guessExtension();
                $motivationFile->move($this->getParameter('motivation_directory'), $letterFilename);
                $application->setLetterMotivation($letterFilename);
            }

            // Set metadata
            $application->setCreatedAt(new \DateTimeImmutable());
            $application->setUtilisateur($user);
            $application->setJobOffer($jobOffer);
            $application->setStatut('en attente');

            $em->persist($application);
            $em->flush();

            // // Send email to candidate
            // $candidateEmail = $user->getUserIdentifier();
            // $subjectCandidate = 'Confirmation de candidature';
            // $bodyCandidate = 'Vous avez postulé à l\'offre : ' . $jobOffer->getTitre();
            // $mailerService->sendEmail($candidateEmail, $subjectCandidate, $bodyCandidate);

            // // Send email to company
            // $companyEmail = $jobOffer->getEntreprise()->getResponsable()->getEmail();
            // $subjectCompany = 'Nouvelle candidature reçue';
            // $bodyCompany = 'Une nouvelle candidature a été reçue pour votre offre : ' . $jobOffer->getTitre();
            // $mailerService->sendEmail($companyEmail, $subjectCompany, $bodyCompany);
            $this->addFlash('success', 'Votre candidature a été envoyée avec succès.');
            return $this->redirectToRoute('job.details', ['id' => $jobOffer->getId()]);
        }

        return $this->render('application/index.html.twig', [
            'form' => $form,
            'jobOffer' => $jobOffer
        ]);
    }

    private function alreadyApplied(User $user, JobOffer $jobOffer, EntityManagerInterface $em): bool
    {
        $application = $em->getRepository(Application::class)->findOneBy([
            'utilisateur' => $user,
            'job_offer' => $jobOffer,
        ]);

        return $application !== null;
    }

    #[Route('/application/download/{type}/{filename}', name: 'application_download')]
    public function downloadFile(string $type, string $filename): Response
    {
        $basePath = $this->getParameter('kernel.project_dir') . '/public/uploads';

        $directory = match ($type) {
            'cv' => $basePath . '/cv',
            'motivation' => $basePath . '/motivation',
            default => throw $this->createNotFoundException('Type de fichier invalide'),
        };

        $filePath = $directory . '/' . $filename;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('Fichier introuvable : ' . $filePath);
        }

        return $this->file($filePath);
    }

    #[Route('/application/{id}/update', name: 'update_application_status', methods: ['POST'])]
    public function updateStatus(Request $request, Application $application, EntityManagerInterface $em): Response
    {
        $newStatus = $request->request->get('statut');
        $comment = $request->request->get('commentaire');

        if ($newStatus === 'Acceptée' && empty($comment)) {
            $this->addFlash('error', 'Un commentaire est obligatoire pour accepter une candidature.');
            return $this->redirectToRoute('entre_application.all');
        }

        $application->setStatut($newStatus);
        $application->setCommentaire($comment);
        $em->flush();

        $this->addFlash('success', 'Statut mis à jour avec succès.');
        return $this->redirectToRoute('entre_application.all');
    }



}

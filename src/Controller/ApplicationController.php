<?php

namespace App\Controller;

use App\Entity\Application;
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

            // Send email to candidate
            $candidateEmail = $user->getUserIdentifier();
            $subjectCandidate = 'Confirmation de candidature';
            $bodyCandidate = 'Vous avez postulé à l\'offre : ' . $jobOffer->getTitre();
            $mailerService->sendEmail($candidateEmail, $subjectCandidate, $bodyCandidate);

            // Send email to company
            $companyEmail = $jobOffer->getEntreprise()->getResponsable()->getEmail();
            $subjectCompany = 'Nouvelle candidature reçue';
            $bodyCompany = 'Une nouvelle candidature a été reçue pour votre offre : ' . $jobOffer->getTitre();
            $mailerService->sendEmail($companyEmail, $subjectCompany, $bodyCompany);

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
}

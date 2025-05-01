<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'main.admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/users', name:'admin.users')]
    public function users_all(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findAll();
        return $this->render('admin/users/index.html.twig', [
            'users' => $users
        ]);
    }
    #[Route('/admin/users/edit/{id}', name:'admin.users.edit')]
    public function users_edit(EntityManagerInterface $em, Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $roles = $user->getRoles();
            if (!in_array('ROLE_ENTERPRISE', $roles)) {
                $user->setCompany(null);
            }else{
                $user->setRoles(['ROLE_ENTERPRISE']);
            }

            $this->addFlash('success', 'User added successfuly');
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('admin.users');
        }
        return $this->render('admin/users/edit.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/admin/users/delete/{id}', name:'admin.users.delete')]
    public function users_delete(EntityManagerInterface $em, Request $request, User $user): Response
    {
        $em->remove($user);
        $this->addFlash('success', 'User deleted successfuly');
        $em->flush();
        return $this->redirectToRoute('admin.users');
    }

    #[Route('/admin/jobs', name:'admin.jobs')]
    public function jobs_all(EntityManagerInterface $em): Response
    {
        $jobOffers = $em->getRepository(JobOffer::class)->findAll();
        return $this->render('/admin/jobs/index.html.twig',[
            "jobOffers" => $jobOffers
        ]);
    }

    #[Route('/admin/jobs/delete/{id}', name:'admin.jobs.delete')]
    public function jobs_delete(EntityManagerInterface $em, JobOffer $job): Response
    {
        $em->remove($job);
        $this->addFlash('success', 'job Offer deleted successfuly');
        $em->flush();
        return $this->redirectToRoute('admin.jobs');
    }
}

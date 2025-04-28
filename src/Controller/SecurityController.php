<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('main.admin');
            } elseif ($this->isGranted('ROLE_ENTERPRISE')) {
                return $this->redirectToRoute('main.entreprise');
            } elseif ($this->isGranted('ROLE_CANDIDATE')) {
                return $this->redirectToRoute('app_home');
            } else {
                return $this->redirectToRoute('job.all');
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    #[Route(path: '/my-account', name: 'app_account')]
    public function my_account(): Response
    {
        $user = $this->getUser();
        return $this->render('security/myaccount.html.twig', [
            "user" => $user
        ]);
    }

    #[Route(path: '/reset-password', name: 'password.reset', methods: ['POST'])]
    public function reset_password(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $id = $request->request->get('id');
        $user = $em->getRepository(User::class)->find($id);
    
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to reset your password.');
        }
    
        $currentPassword = $request->request->get('currentPassword');
        $newPassword = $request->request->get('newPassword');
        $confirmPassword = $request->request->get('confirmPassword');
    
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->addFlash('error', 'All fields are required.');
            return $this->redirectToRoute('app_account');
        }
    
        // Vérifier que le mot de passe actuel est correct
        if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
            $this->addFlash('error', 'Current password is incorrect.');
            return $this->redirectToRoute('app_account');
        }
    
        // Vérifier que les nouveaux mots de passe correspondent
        if ($newPassword !== $confirmPassword) {
            $this->addFlash('error', 'New passwords do not match.');
            return $this->redirectToRoute('app_account');
        }
    
        // Mettre à jour le mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
    
        $em->persist($user);
        $this->addFlash('message', 'Mot de pass mise à jour avec succès');
        $em->flush();
    
        $this->addFlash('success', 'Your password has been updated successfully.');
    
        return $this->redirectToRoute('app_account');
    }
    
}

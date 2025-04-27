<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    public function my_account() : Response{
        $user = $this->getUser();
        return $this->render('security/myaccount.html.twig', [
            "user" => $user
        ]);
    }
}

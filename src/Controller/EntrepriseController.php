<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'main.entreprise')]
    public function index(UserRepository $userRepository): Response
    {
        $useremail = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findByemail($useremail);
        if($user){
            $comapny = $user[0]->getCompany();
        }
        return $this->render('entreprise/index.html.twig', [
            'company' => $comapny,
        ]);
    }
}

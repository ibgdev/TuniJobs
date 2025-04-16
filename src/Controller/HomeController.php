<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em): Response
    {
        if($this->getUser()){
            if ($this->getUser()->getRoles()[0] == 'ROLE_ADMIN') {
                return $this->redirectToRoute('main.admin');
            }
            if ($this->getUser()->getRoles()[0] == 'ROLE_ENTERPRISE') {
                return $this->redirectToRoute('main.entreprise');
            }
        }
        $offers = $em->getRepository('App\Entity\JobOffer')->findAll();
        return $this->render('home/index.html.twig', [
        'featuredJobs' => $offers,
        ]);
    }
}

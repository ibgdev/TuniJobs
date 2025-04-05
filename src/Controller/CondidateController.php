<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CondidateController extends AbstractController
{
    #[Route('/condidate', name: 'main.condidate')]
    public function index(): Response
    {
        return $this->render('condidate/index.html.twig', [
            'controller_name' => 'CondidateController',
        ]);
    }
}

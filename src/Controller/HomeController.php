<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\JobOffer;
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
        $offers = $em->getRepository(JobOffer::class)->findAll();
        $categories = $em->getRepository(Category::class)->findAll();
        return $this->render('home/index.html.twig', [
        'featuredJobs' => $offers,
        'categories' => $categories,
        'locations' => $this->getTunisianGovernorates(),
        ]);
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

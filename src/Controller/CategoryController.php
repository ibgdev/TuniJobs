<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'category.all')]
    public function index(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }
    #[Route('/category/add', name: 'category.add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category.all');
        }
        return $this->render('category/add.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/category/edit/{id}', name: 'category.edit')]
    public function edit(EntityManagerInterface $em, Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category.all');
        }
        return $this->render('category/edit.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/category/delete/{id}', name: 'category.delete')]
    public function delete( EntityManagerInterface $em, Category $category): Response
    {
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category.all');
    }
}

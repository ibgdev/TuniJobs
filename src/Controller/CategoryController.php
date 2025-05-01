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
    #[Route('/admin/categories', name: 'category.all')]
    public function index(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories
        ]);
    }
    #[Route('/admin/category/add', name: 'category.add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Category added successfuly');
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category.all');
        }
        return $this->render('admin/category/add.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/admin/category/edit/{id}', name: 'category.edit')]
    public function edit(EntityManagerInterface $em, Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Category updated successfuly');
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category.all');
        }
        return $this->render('admin/category/edit.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/admin/category/delete/{id}', name: 'category.delete')]
    public function delete( EntityManagerInterface $em, Category $category): Response
    {
        $em->remove($category);
        $this->addFlash('success', 'Category deleted successfuly');
        $em->flush();
        return $this->redirectToRoute('category.all');
    }
}

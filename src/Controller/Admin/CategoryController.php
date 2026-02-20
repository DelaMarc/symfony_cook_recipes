<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Form\CategoryType;

#[Route("/admin/category", name:"admin.category.")]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $repository): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'categories' => $repository->findAll()]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'La catégorie a bien été créée');
            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/category/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: "edit", methods:['GET', 'POST'], requirements : ['id' => Requirement::DIGITS])]
    public function edit(Request $request, Category $category, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->addFlash('success', 'La catégorie a bien été mise à jour');
            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/category/edit.html.twig', [
            'form' => $form,
            'category' => $category,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods:['DELETE'], requirements : ['id' => Requirement::DIGITS])]
    public function delete(Category $category, EntityManagerInterface $em): Response
    {
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'La catégorie a bien été supprimée');
        return $this->redirectToRoute('admin.category.index');
    }
}
<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Form\RecipeType;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;

#[Route("/admin/recettes", name:"admin.recipe.")]
final class RecipeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, RecipeRepository $repository): Response
    {
        $recipes = $repository->findWithDurationLowerThan(20);
        //dd($recipes);
        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            // $now = new DateTimeImmutable();
            // $recipe->setCreatedAT($now);
            // $recipe->setUpdatedAt($now);
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été créée');
            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render('admin/recipe/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: "edit", methods:['GET', 'POST'], requirements : ['id' => Requirement::DIGITS])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            //$recipe->setUpdatedAt(new DateTimeImmutable());
            $em->flush();
            $this->addFlash('success', 'La recette a bien été modifiée');
            return $this->redirectToRoute('admin.recipe.index');
        }

        return $this->render('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods:['DELETE'], requirements : ['id' => Requirement::DIGITS])]
    public function delete(Recipe $recipe, EntityManagerInterface $em)
    {
        //dd($recipe);
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'La recette a bien été supprimée');
        return $this->redirectToRoute('admin.recipe.index');
    }
}

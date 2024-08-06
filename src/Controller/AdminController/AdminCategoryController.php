<?php

namespace App\Controller\AdminController;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminCategoryController extends AbstractController
{
    #[Route('/admin/category', name: 'admin_category')]
    public function index(CategoryRepository $categoryRepository){

        $categories = $categoryRepository->findAll();

        return $this->render('page/admin/category/admin_liste_category.html.twig', ['categories' => $categories]);
    }


    #[Route('/admin/category/delete/{idCategory}', name: 'admin_category_delete')]
    public function delete_category(int $idCategory, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        $category = $categoryRepository->find($idCategory);

        if (!$category) {
            $html404 = $this->renderView('page/admin/category/adminE404.html.twig');
            return new Response($html404, 404);
        }
        try {
            $entityManager->remove($category);
            $entityManager->flush();
            // permet d'enregistrer un message dans la session de PHP
            // ce message sera affiché grâce à twig sur la prochaine page
            $this->addFlash('success', 'Catégory bien supprimé !');

        }catch (\Exception $exception){

            return $this->renderView('page/admin/article/adminE404.html.twig', ['errorMessage' => $exception->getMessage()]);

        }

        return $this->redirectToRoute('admin_category');

    }


    #[Route('/admin/category/add', name: 'add_category')]
    public function add_category(Request $request, EntityManagerInterface $entityManager){

        $category = new Category();
        $form_add_category = $this->createForm(CategoryType::class, $category);
        $form_add_category->handleRequest($request);

        if($form_add_category->isSubmitted() && $form_add_category->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'category enregistré');

        }
        return $this->render('page/admin/category/admin_add_category.html.twig', ['categoryForm' => $form_add_category->createView()]);

    }

    #[Route('/{idCategory}/edit', name: 'admin_update_category', methods: ['GET', 'POST'])]
    public function edit(int $idCategory, Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        $category = $categoryRepository->find($idCategory);
        // Quand on demande à Symfony d'instancier une entité en parametre
        // d'un controleur et qu'on a un id en parametre de la route
        // symfonty va automatiquement essayer de récupérer un enregistrement
        // dans la table reliée, correspondant à l'id (equivalent au categoryRepository->find($idCategory)
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_category', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('page/admin/category/edit.html.twig', [
            'category' => $category,
            'categoryForm' => $form,
        ]);
    }

}
<?php

namespace App\Controller\PublicController;


use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'categories')]
    public function liste_categories(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        return $this->render('page/category/liste_category.html.twig', ['categories' => $categories]);
    }

    #[Route('/categories/{idCategory}', name: 'show_category')]
    public function categorie_choice(CategoryRepository $categoryRepository, $idCategory)
    {
        $category = $categoryRepository->find($idCategory);

        if(!$category){
            $html404 = $this->renderView('page/404.html.twig');
            return new Response($html404, 404);
        }

        return $this->render('page/category/category_choice.html.twig', ['category' => $category]);
    }
}
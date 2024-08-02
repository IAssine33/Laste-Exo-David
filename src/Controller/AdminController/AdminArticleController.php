<?php

namespace App\Controller\AdminController;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminArticleController extends AbstractController
{

    #[Route('/admin/article', name: 'admin_article')]
    public function index(ArticleRepository $articleRepository){

        $articles = $articleRepository->findAll();

        return $this->render('page/admin/admine_liste_article.html.twig', ['articles' => $articles]);
    }


    #[Route('/admin/article/delete/{idArticle}', name: 'admin_article_delete')]
    public function delete_article(int $idArticle, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        $article = $articleRepository->find($idArticle);

        if (!$article) {
            $html404 = $this->renderView('page/admin/adminE404.html.twig');
            return new Response($html404, 404);
        }
        try {
            $entityManager->remove($article);
            $entityManager->flush();
            // permet d'enregistrer un message dans la session de PHP
            // ce message sera affiché grâce à twig sur la prochaine page
            $this->addFlash('success', 'Article bien supprimé !');

        }catch (\Exception $exception){

            return $this->renderView('page/admin/adminE404.html.twig', ['errorMessage' => $exception->getMessage()]);

        }

        return $this->redirectToRoute('admin_article');

    }


    #[Route('/admin/article/add', name: 'add_article')]
    public function add_article(Request $request, EntityManagerInterface $entityManager){

        $article = new Article();
        $form_add_article = $this->createForm(ArticleType::class, $article);
        $form_add_article->handleRequest($request);

        if($form_add_article->isSubmitted() && $form_add_article->isValid()){
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'article enregistré');

        }
        return $this->render('page/admin/admin_add_article.html.twig', ['articleForm' => $form_add_article->createView()]);

    }

}
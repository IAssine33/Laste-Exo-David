<?php

namespace App\Controller\PublicController;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    #[Route('/articles', name: 'articles')]
    public function liste_articles(ArticleRepository $articleRepository){

        $articles = $articleRepository->findAll();
        return $this->render('page/article/liste_articles.html.twig', ['articles' => $articles]);
    }

    #[Route('/articles/choice/{idArticle}', name: 'article_choice')]
    public function article_choiced(ArticleRepository $articleRepository, $idArticle): Response
    {
        $article = $articleRepository->find($idArticle);

        if (!$article || !$article->isPublished()) {
            $html404 = $this->renderView('page/404.html.twig');
            return new Response($html404, 404);
        }

        return $this->render('page/article/article_choice.html.twig', ['article' => $article]);
    }
}
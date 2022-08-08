<?php

namespace App\Controller\Web;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('web/index.html.twig', [
            'articles' => $articleRepository->findBy([], ['date_created' => 'desc']),
        ]);
    }

    #[Route('/article/{id}-{slug}', name: 'app_article')]
    public function article(Article $article): Response
    {
        return $this->render('web/article/article.html.twig', [
            'article' => $article,
        ]);
    }


}

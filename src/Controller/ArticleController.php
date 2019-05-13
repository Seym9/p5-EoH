<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesCategoriesRepository;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function articleSummary(ArticlesRepository $repo, ArticlesCategoriesRepository $cat)
    {
        $articles = $repo->findAll();
        $categories = $cat->findAll();

        return $this->render('article/articlesSummary.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/article/{id}", name="articleRead")
     */
    public function articleRead (Articles $article){

        return $this->render('article/articleRead.html.twig', [
           'controller_name' => 'ArticleController',
            'article' => $article,
        ]);
    }
}

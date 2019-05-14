<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\ArticlesComments;
use App\Entity\Users;
use App\Form\ArticleCommentType;
use App\Repository\ArticlesCategoriesRepository;
use App\Repository\ArticlesRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
    public function articleRead (Articles $article, Request $request, ObjectManager $manager, Security $security){
        $user = $security->getUser();
//        dump($user);
//        die();
        $comment = new ArticlesComments();
        $form = $this->createForm(ArticleCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article)
                    ->setAuthor($user->getId());

            $manager->persist($comment);
            $manager->flush();

        }

        return $this->render('article/articleRead.html.twig', [
           'controller_name' => 'ArticleController',
            'article' => $article,
            'commentForm' => $form->createView(),
        ]);
    }
}

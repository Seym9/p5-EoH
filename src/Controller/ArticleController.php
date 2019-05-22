<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\ArticlesComments;
use App\Form\ArticleCommentType;
use App\Form\ArticleCreationType;
use App\Repository\ArticlesCategoriesRepository;
use App\Repository\ArticlesRepository;
use DateTime;
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

        $comment = new ArticlesComments();
        $form = $this->createForm(ArticleCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article)
                    ->setAuthor($user);

            $manager->persist($comment);
            $manager->flush();

        }

        return $this->render('article/articleRead.html.twig', [
           'controller_name' => 'ArticleController',
            'article' => $article,
            'commentForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create-article", name="article_creation")
     */
    public function createArticle(Request $request, ObjectManager $manager, Security $security){
        $user = $security->getUser();
        $article = new Articles();

        $form = $this->createForm(ArticleCreationType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
                $article->setCreatedAt(new DateTime())
                        ->setAuthor($user);

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('articleRead' , ['id' => $article->getId()]);
        }
        return $this->render('admin/createArticle.html.twig', [
            'formArticle' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\ArticlesComments;
use App\Entity\Image;
use App\Form\ArticleCommentType;
use App\Form\ArticleCreationType;
use App\Repository\ArticlesCategoriesRepository;
use App\Repository\ArticlesRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/page-{page}", name="article")
     * @param ArticlesRepository $articlesRepository
     * @param ArticlesCategoriesRepository $cat
     * @param $page
     * @return Response
     * @throws NonUniqueResultException
     */
    public function articleSummary(ArticlesRepository $articlesRepository, ArticlesCategoriesRepository $cat, $page) {
        $categories = $cat->findAll();

        $nb_articles 		= $articlesRepository->FindAllAsInt();
        $nb_articles_page 	= 6;
        $nb_pages 			=  ceil($nb_articles / $nb_articles_page);
        $offset 			= ($page-1) * $nb_articles_page;

        $articles	= $articlesRepository->FindByPage($nb_articles_page ,$offset);

        if(!$articles ){
            throw $this->createNotFoundException('La page demandée n\'existe pas');
        }

        return $this->render('article/articlesSummary.html.twig', array(
            'articles' => $articles,
            'page'		=> $page,
            'nb_pages'	=> $nb_pages,
            'categories' => $categories,
        ));
    }

    /**
     * @Route("/article/{id}", name="articleRead")
     * @param Articles $article
     * @param Request $request
     * @param ObjectManager $manager
     * @param Security $security
     * @return Response
     * @throws Exception
     */
    public function articleRead (Articles $article, Request $request, ObjectManager $manager, Security $security){
        $user = $this->getUser();

        if ($article->getImage() != null){
            $imgName = $article->getImage()->getName();
            $path = 'img/uploaded-img/article-img/' . $imgName ;
        }else{
            $path = null;
        }

        $comment = new ArticlesComments();
        $form = $this->createForm(ArticleCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if($form->getViewData()->getContent() === null){
                return $this->render('article/articleRead.html.twig', [
                    'controller_name' => 'ArticleController',
                    'article' => $article,
                    'commentForm' => $form->createView(),
                ]);
            }
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
            'path' => $path
        ]);
    }

    /**
     * @Route("/create-article", name="article_creation")
     * @Route ("/edit-article/{id}", name="article_edit")
     * @param Request $request
     * @param ObjectManager $manager
     * @param Articles|null $article
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function createArticle(Request $request, ObjectManager $manager, Articles $article = null){
        $user = $this->getUser();
        if (!$article){
            $article = new Articles();
        }

        $form = $this->createForm(ArticleCreationType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if ($article->getImage() && !$article->getId()){
                /** @var Image $image */
                $image = $article->getImage();
                /** @var UploadedFile $file */
                $file = $image->getFile();

                $name = md5(uniqid()). '.' .$file->guessExtension();
                $file->move("../public/img/uploaded-img/article-img", $name);
                $image->setName($name);
            }

            if (!$article->getId()){
                $article->setCreatedAt(new DateTime())
                    ->setAuthor($user);
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('articleRead' , ['id' => $article->getId()]);
        }
        return $this->render('admin/createArticle.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    /**
     * @Route ("/article-comment-report/{id}", name="articleComment_report")
     * @param ArticlesComments $articlesComment
     * @param ObjectManager $manager
     * @return RedirectResponse
     */
    public function articleCommentReport(ArticlesComments $articlesComment, ObjectManager $manager){
        $articlesComment->setReport($articlesComment->getReport() + 1);

        $manager->persist($articlesComment);
        $manager->flush();

        return $this->redirectToRoute('home');
    }
}

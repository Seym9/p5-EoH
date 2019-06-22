<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Topics;
use App\Entity\Users;
use App\Repository\ArticlesCommentsRepository;
use App\Repository\ArticlesRepository;
use App\Repository\TopicsCommentsRepository;
use App\Repository\TopicsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/article-delete/{id}" ,name="article_delete")
     * @param $id
     * @return Response
     */
    public function delArticle($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Articles::class)->find($id);

        $entityManager->remove($article);
        $entityManager->flush();
        $this->addFlash("success", "Cet article a bien été supprimer");

        return $this->json([
            'code' => 200,
            'message' => 'Article delete',
        ], 200);
    }

    /**
     * @Route("/admin-articles/{page}", name="administration_article")
     * @param ArticlesRepository $articlesRepository
     * @param $page
     * @return Response
     * @throws NonUniqueResultException
     */
    public function articleAdminView(ArticlesRepository $articlesRepository, $page){

        $nb_articles 		= $articlesRepository->FindAllAsInt();
        $nb_articles_page 	= 12;
        $nb_pages 			=  ceil($nb_articles / $nb_articles_page);
        $offset 			= ($page-1) * $nb_articles_page;

        $articles	= $articlesRepository->FindByPage($nb_articles_page ,$offset);

        if(!$articles ){
            throw $this->createNotFoundException('La page demandée n\'existe pas');
        }

        return $this->render('admin/articleAdministration.html.twig', array(
            'articles' => $articles,
            'page'		=> $page,
            'nb_pages'	=> $nb_pages,
        ));
    }

    /**
     * @Route("/admin-users/{page}", name="administration_users")
     * @param UsersRepository $topicsRepository
     * @param $page
     * @return Response
     * @throws NonUniqueResultException
     */
    public function usersAdminView(UsersRepository $topicsRepository, $page){

        $nb_users 		    = $topicsRepository->findAllAsInt();
        $nb_users_page 	    = 12;
        $nb_pages 			=  ceil($nb_users / $nb_users_page);
        $offset 			= ($page-1) * $nb_users_page;

        $users	= $topicsRepository->findByPage($nb_users_page ,$offset);

        if(!$users ){
            throw $this->createNotFoundException('La page demandée n\'existe pas');
        }

        return $this->render('admin/usersAdministration.html.twig', array(
            'users'    => $users,
            'page'		=> $page,
            'nb_pages'	=> $nb_pages,
        ));
    }

    /**
     * @Route("/user-delete/{id}" ,name="user_delete")
     * @param $id
     * @return Response
     */
    public function delUser($id){
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(Users::class)->find($id);

        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash("success", "Cet utilisateur a été supprimer");

        return $this->json([
            'code' => 200,
            'message' => 'Utilisateur delete',
        ], 200);
    }

    /**
     * @Route("/admin-forum/{page}", name="administration_forum")
     * @param TopicsRepository $topicsRepository
     * @param $page
     * @return Response
     * @throws NonUniqueResultException
     */
    public function forumAdminView(TopicsRepository $topicsRepository, $page){

        $nb_topic 		= $topicsRepository->FindAllAsInt();
        $nb_topics_page 	= 12;
        $nb_pages 			=  ceil($nb_topic / $nb_topics_page);
        $offset 			= ($page-1) * $nb_topics_page;

        $topics	= $topicsRepository->FindByPage($nb_topics_page ,$offset);

        if(!$topics ){
            throw $this->createNotFoundException('La page demandée n\'existe pas');
        }

        return $this->render('admin/forumAdministration.html.twig', array(
            'topics'  => $topics,
            'page'		=> $page,
            'nb_pages'	=> $nb_pages,
        ));
    }

    /**
     * @Route("/topic-delete/{id}" ,name="topic_delete")
     * @param $id
     * @return Response
     */
    public function delTopic($id){
        $entityManager = $this->getDoctrine()->getManager();
        $topic = $entityManager->getRepository(Topics::class)->find($id);

        $entityManager->remove($topic);
        $entityManager->flush();
        $this->addFlash("success", "Ce topic a bien été supprimer");
        return $this->json([
            'code' => 200,
            'message' => 'Topic delete',
        ], 200);
    }

    /**
     * @Route("/admin/dashboard",name="admin_dashboard")
     * @param ArticlesRepository $articlesRepository
     * @param TopicsRepository $topicsRepository
     * @param TopicsCommentsRepository $topicsCommentsRepository
     * @param ArticlesCommentsRepository $articlesCommentsRepository
     * @param UsersRepository $usersRepository
     * @return Response
     */
    public function dashboard(ArticlesRepository $articlesRepository, TopicsRepository $topicsRepository, TopicsCommentsRepository $topicsCommentsRepository, ArticlesCommentsRepository $articlesCommentsRepository, UsersRepository $usersRepository){
        $articles = $articlesRepository->findAll();
        $topics = $topicsRepository->findAll();
        $topicsComments = $topicsCommentsRepository->findAll();
        $articlesComments = $articlesCommentsRepository->findAll();
        $users = $usersRepository->findAll();

        return $this->render("admin/dashboard.html.twig",[
            "articles" => $articles,
            "topics" => $topics,
            "topicsComments" => $topicsComments,
            "articlesComments" => $articlesComments,
            "users" => $users
        ]);
    }
}

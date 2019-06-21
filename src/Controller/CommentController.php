<?php

namespace App\Controller;

use App\Entity\ArticleCommentReport;
use App\Entity\ArticlesComments;
use App\Entity\TopicCommentReport;
use App\Entity\TopicsComments;
use App\Repository\ArticlesCommentsRepository;
use App\Repository\TopicsCommentsRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index() {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    /**
     * @Route ("/comment-topic/{id}/report", name="report_topic_comment")
     * @param TopicsComments $topicsComments
     * @param ObjectManager $manager
     * @return Response
     * @throws Exception
     */
    public function reportTopicComment(TopicsComments $topicsComments, ObjectManager $manager){
        $user = $this->getUser();

        if (!$user) return $this->json([
            'code' => 403,
            'message' => "Unauthorized"
        ], 403);

        if ($topicsComments->isReportedByUser($user)){
            return $this->json([
                'code' => 403,
                'message' => 'Already reported',
            ], 403);
        }

        $report = new TopicCommentReport();
        $report
            ->setUser($user)
            ->setComment($topicsComments)
            ->setCreatedAt( new DateTime());

        $addReport = $topicsComments->getReport() + 1;
        $reportadd = $topicsComments;
        $reportadd
            ->setReport( $addReport);

        $manager->persist($report);
        $manager->persist($reportadd);
        $manager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'Reported',
        ], 200);
    }

    /**
     * @Route ("/comment-article/{id}/report", name="report_article_comment")
     * @param ArticlesComments $articlesComments
     * @param ObjectManager $manager
     * @return Response
     * @throws Exception
     */
    public function reportArticleComment(ArticlesComments $articlesComments, ObjectManager $manager){
        $user = $this->getUser();

        if (!$user) return $this->json([
            'code' => 403,
            'message' => "Unauthorized"
        ], 403);

        if ($articlesComments->isReportedByUser($user)){
            return $this->json([
                'code' => 403,
                'message' => 'Already reported',
            ], 403);
        }

        $report = new ArticleCommentReport();
        $report
            ->setUser($user)
            ->setComment($articlesComments)
            ->setCreatedAt( new DateTime());

        $addReport = $articlesComments->getReport() + 1;
        $reportadd = $articlesComments;
        $reportadd
            ->setReport($addReport);

        $manager->persist($reportadd);
        $manager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'Reported',
        ], 200);
    }


    /**
     * @Route("/admin-forum-comment/{page}", name="administration_forumComments")
     * @param TopicsCommentsRepository $topicsCommentsRepository
     * @param $page
     * @return Response
     * @throws NonUniqueResultException
     */
    public function forumCommentsAdminView(TopicsCommentsRepository $topicsCommentsRepository, $page){

        $nb_topics 		= $topicsCommentsRepository->FindAllAsInt();
        $nb_topics_page 	= 12;
        $nb_pages 			=  ceil($nb_topics / $nb_topics_page);
        $offset 			= ($page-1) * $nb_topics_page;

        $topics	= $topicsCommentsRepository->FindByPage($nb_topics_page ,$offset);

        if(!$topics ){
            throw $this->createNotFoundException('La page demandée n\'existe pas');
        }

        return $this->render('admin/forumCommentAdministration.html.twig', array(
            'topics' => $topics,
            'page'		=> $page,
            'nb_pages'	=> $nb_pages,
        ));
    }

    /**
     * @Route("/admin-article-comment/{page}", name="administration_articleComments")
     * @param ArticlesCommentsRepository $articlesCommentsRepository
     * @param $page
     * @return Response
     * @throws NonUniqueResultException
     */
    public function articleCommentsAdminView(ArticlesCommentsRepository $articlesCommentsRepository , $page){

        $nb_article 		= $articlesCommentsRepository->FindAllAsInt();
        $nb_articles_page 	= 12;
        $nb_pages 			=  ceil($nb_article / $nb_articles_page);
        $offset 			= ($page-1) * $nb_articles_page;

        $articles	= $articlesCommentsRepository->FindByPage($nb_articles_page ,$offset);

        if(!$articles ){
            throw $this->createNotFoundException('La page demandée n\'existe pas');
        }

        return $this->render('admin/articleCommentAdministration.html.twig', array(
            'articles'  => $articles,
            'page'		=> $page,
            'nb_pages'	=> $nb_pages,
        ));
    }

    /**
     * @Route("/comment-article-delete/{id}" ,name="articleComment_delete")
     * @param $id
     * @return Response
     */
    public function delArticleComment($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $articleComment = $entityManager->getRepository(ArticlesComments::class)->find($id);

        $entityManager->remove($articleComment);
        $entityManager->flush();
        $this->addFlash("success", "Ce message a ");

        return $this->json([
            'code' => 200,
            'message' => 'Article delete',
        ], 200);
    }

    /**
     * @Route("/comment-topic-delete/{id}" ,name="topicComment_delete")
     * @param $id
     * @return Response
     */
    public function delTopicComment($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $articleComment = $entityManager->getRepository(TopicsComments::class)->find($id);

        $entityManager->remove($articleComment);
        $entityManager->flush();
        $this->addFlash("success", "Ce message a ");

        return $this->json([
            'code' => 200,
            'message' => 'Article delete',
        ], 200);
    }
}

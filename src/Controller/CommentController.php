<?php

namespace App\Controller;

use App\Entity\ArticleCommentReport;
use App\Entity\ArticlesComments;
use App\Entity\TopicCommentReport;
use App\Entity\TopicReport;
use App\Entity\Topics;
use App\Entity\TopicsComments;
use App\Repository\ArticleCommentReportRepository;
use App\Repository\ArticlesCommentsRepository;
use App\Repository\TopicCommentReportRepository;
use App\Repository\TopicReportRepository;
use App\Repository\TopicsCommentsRepository;
use App\Repository\TopicsRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index()
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    /**
     * Allow to report topics
     *
     * @Route ("/comment-topic/{id}/report", name="report_topic_comment")
     *
     * @param TopicsComments $topicsComments
     * @param ObjectManager $manager
     * @param TopicCommentReportRepository $reportRepository
     * @return Response
     * @throws \Exception
     */
    public function reportTopicComment(TopicsComments $topicsComments, ObjectManager $manager, TopicCommentReportRepository $reportRepository){
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

        $manager->persist($report);
        $manager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'Reported',
        ], 200);
    }

    /**
     * @Route ("/comment-article/{id}/report", name="report_article_comment")
     *
     * @param ArticlesComments $articlesComments
     * @param ObjectManager $manager
     * @param ArticleCommentReportRepository $reportRepository
     * @return Response
     * @throws \Exception
     */
    public function reportArticleComment(ArticlesComments $articlesComments, ObjectManager $manager, ArticleCommentReportRepository $reportRepository){
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

        $manager->persist($report);
        $manager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'Reported',
        ], 200);
    }

    /**
     * @Route("/admin-forum-comment", name="administration_forumComments")
     *
     * @param TopicsCommentsRepository $topicsCommentsRepository
     * @return Response
     */
    public function forumCommentsAdminView(TopicsCommentsRepository $topicsCommentsRepository){

        $topics = $topicsCommentsRepository->findAll();

        return $this->render('admin/forumCommentAdministration.html.twig', array(
            'topics'    => $topics,
        ));
    }

    /**
     * @Route("/admin-article-comment", name="administration_articleComments")
     *
     * @param ArticlesCommentsRepository $articlesCommentsRepository
     * @return Response
     */
    public function articleCommentsAdminView(ArticlesCommentsRepository $articlesCommentsRepository){

        $articles = $articlesCommentsRepository->findAll();

        return $this->render('admin/articleCommentAdministration.html.twig', array(
            'articles'    => $articles,
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

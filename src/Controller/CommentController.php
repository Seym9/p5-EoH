<?php

namespace App\Controller;

use App\Entity\ArticleCommentReport;
use App\Entity\ArticlesComments;
use App\Entity\TopicCommentReport;
use App\Entity\TopicReport;
use App\Entity\Topics;
use App\Entity\TopicsComments;
use App\Repository\ArticleCommentReportRepository;
use App\Repository\TopicCommentReportRepository;
use App\Repository\TopicReportRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
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
     * @param Topics $topic
     * @param ObjectManager $manager
     * @param TopicReportRepository $reportRepository
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
            'report' => $reportRepository->count(['topic' => $topicsComments])
        ], 200);
    }

    /**
     * Allow to report topics
     *
     * @Route ("/comment-article/{id}/report", name="report_article_comment")
     *
     * @param Topics $topic
     * @param ObjectManager $manager
     * @param TopicReportRepository $reportRepository
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
            'report' => $reportRepository->count(['topic' => $articlesComments])
        ], 200);
    }
}

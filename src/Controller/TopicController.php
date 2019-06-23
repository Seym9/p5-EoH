<?php

namespace App\Controller;

use App\Entity\ForumCategories;

use App\Entity\TopicLike;
use App\Entity\TopicReport;
use App\Entity\Topics;
use App\Entity\TopicsComments;
use App\Form\TopicsCommentsType;
use App\Form\TopicType;
use App\Repository\ForumCategoriesRepository;
use App\Repository\TopicLikeRepository;
use App\Repository\TopicReportRepository;
use App\Repository\TopicsRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TopicController extends AbstractController
{


    /**
     * @Route("/forum-home", name="forum_home")
     * @param TopicsRepository $repotopics
     * @param ForumCategoriesRepository $repocategory
     * @return Response
     */
    public function forumHome(TopicsRepository $repotopics, ForumCategoriesRepository $repocategory){
        $categories = $repocategory->findAll();
        $topics = $repotopics->findByThree();

        return $this->render("topic/forumHome.html.twig",[
            'topics' => $topics,
            'categories' =>$categories
        ]);
    }

    /**
     * @Route ("/forum-category/{id}", name="forum_category_view")
     * @param ForumCategories $category
     * @return Response
     */
    public function categoryView (ForumCategories $category) {



        return $this->render("topic/categoryView.html.twig",[
            'category' => $category
        ]);
    }

    /**
     * @Route ("/topic/{id}", name="topic_view")
     * @param Topics $topic
     * @param Request $request
     * @param ObjectManager $manager
     * @param Security $security
     * @return Response
     * @throws Exception
     */
    public function topicView(Topics $topic, Request $request, ObjectManager $manager, Security $security){
        $user = $security->getUser();

        $comment = new TopicsComments();
        $form = $this->createForm(TopicsCommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if($form->getViewData()->getContent() === null){
                return $this->render("topic/topicView.html.twig",[
                    'topic' => $topic,
                    'commentForm' => $form->createView()
                ]);
            }
            $comment->setCreatedAt(new \DateTime())
                    ->setTopic($topic)
                    ->setAuthor($user);

            $manager->persist($comment);
            $manager->flush();
        }

        return $this->render("topic/topicView.html.twig",[
            'topic' => $topic,
            'commentForm' => $form->createView()
        ]);
    }

    /**
     * @Route ("/create-topic", name="topic_creation")
     * @param Request $request
     * @param ObjectManager $manager
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function createTopic(Request $request, ObjectManager $manager){
        $user = $this->getUser();
        $topic = new Topics();

        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $topic->setCreatedAt(new DateTime())
                ->setAuthor($user);

            $manager->persist($topic);
            $manager->flush();

            return $this->redirectToRoute('topic_view' , ['id' => $topic->getId()]);
        }

        return $this->render('topic/createTopic.html.twig', [
            'formTopic' => $form->createView(),
        ]);
    }

    /**
     * @Route("/topic/{id}/like", name="topic_like")
     * @param Topics $topic
     * @param ObjectManager $manager
     * @param TopicLikeRepository $likeRepository
     * @return Response
     * @throws Exception
     */
    public function like (Topics $topic, ObjectManager $manager, TopicLikeRepository $likeRepository) : Response{
        $user = $this->getUser();

        if (!$user) return $this->json([
            'code' => 403,
            'message' => "Unauthorized"
        ], 403);

        if ($topic->isLikeByUser($user)){
            $like = $likeRepository->findOneBy([
                'topic' => $topic,
                'user' => $user
            ]);
            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like deleted',
                'likes' => $likeRepository->count(['topic' => $topic])
            ], 200);
        }

        $like = new TopicLike();
        $like
            ->setTopic($topic)
            ->setUser($user)
            ->setCreatedAt(new DateTime());

        $manager->persist($like);
        $manager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'Like Added',
            'likes' => $likeRepository->count(['topic' => $topic])
        ], 200);
    }


    /**
     * @Route ("/topic/{id}/report", name="report_topic")
     * @param Topics $topic
     * @param ObjectManager $manager
     * @param TopicReportRepository $reportRepository
     * @return Response
     * @throws Exception
     */
    public function reportTopic(Topics $topic, ObjectManager $manager, TopicReportRepository $reportRepository){
        $user = $this->getUser();

        if (!$user) return $this->json([
            'code' => 403,
            'message' => "Unauthorized"
        ], 403);

        if ($topic->isReportedByUser($user)){
            return $this->json([
                'code' => 403,
                'message' => 'Already reported',
            ], 403);
        }

        $report = new TopicReport();
        $report
            ->setUser($user)
            ->setTopic($topic)
            ->setCreatedAt( new DateTime());

        $addReport = $topic->getReport() + 1;
        $reportadd = $topic;
        $reportadd
            ->setReport($addReport);

        $manager->persist($report);
        $manager->persist($reportadd);
        $manager->flush();
        return $this->json([
            'code' => 200,
            'message' => 'TopicReported',
        ], 200);
    }
}

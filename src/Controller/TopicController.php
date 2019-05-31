<?php

namespace App\Controller;

use App\Entity\ForumCategories;

use App\Entity\Topics;
use App\Entity\TopicsComments;
use App\Entity\Users;
use App\Form\TopicsCommentsType;
use App\Form\TopicType;
use App\Repository\ForumCategoriesRepository;
use App\Repository\TopicsRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TopicController extends AbstractController
{


    /**
     * @Route("/forum-home", name="forum_home")
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
     */
    public function categoryView (ForumCategories $category) {


        return $this->render("topic/categoryView.html.twig",[
            'category' => $category
        ]);
    }

    /**
     * @Route ("/topic/{id}", name="topic_view")
     */
    public function topicView(Topics $topic, Request $request, ObjectManager $manager, Security $security){
        $user = $security->getUser();

        $comment = new TopicsComments();
        $form = $this->createForm(TopicsCommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
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
     */
    public function createTopic(Request $request, ObjectManager $manager, Security $security){
        $user = $security->getUser();
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

        return $this->render('admin/createTopic.html.twig', [
            'formTopic' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/topic-report/{id}", name="topic_report")
     */
    public function topicReport(Topics $topic, ObjectManager $manager){
        $topic->setReport($topic->getReport() + 1);

        $manager->persist($topic);
        $manager->flush();

        return $this->redirectToRoute('home');
    }
    /**
     * @Route ("/topic-comment-report/{id}", name="topicComment_report")
     */
    public function articleCommentReport(TopicsComments $topicComment, ObjectManager $manager){
        $topicComment->setReport($topicComment->getReport() + 1);

        $manager->persist($topicComment);
        $manager->flush();

        return $this->redirectToRoute('home');
    }

}

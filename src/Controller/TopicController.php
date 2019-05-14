<?php

namespace App\Controller;

use App\Entity\Topics;
use App\Repository\ForumCategoriesRepository;
use App\Repository\TopicsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}

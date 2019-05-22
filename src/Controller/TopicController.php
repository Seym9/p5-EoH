<?php

namespace App\Controller;

use App\Entity\ForumCategories;

use App\Repository\ForumCategoriesRepository;
use App\Repository\TopicsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    public function topicView(){

        return $this->render("topic/topicView.html.twig");
    }
}

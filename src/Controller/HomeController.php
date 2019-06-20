<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use App\Repository\TopicsCommentsRepository;
use App\Repository\TopicsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param ArticlesRepository $repo
     * @param TopicsRepository $topic
     * @return Response
     */
    public function home(ArticlesRepository $repo, TopicsRepository $topic)
    {
        $articles = $repo->findOneBy(array(), array('id' => 'DESC'));
        $topics = $topic->findByThree();

        return $this->render('home/home.html.twig', [
            'topics' => $topics,
            'articles' => $articles,
            'controller_name' => 'HomeController'
        ]);
    }
}

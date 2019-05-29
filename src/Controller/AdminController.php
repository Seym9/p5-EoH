<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Topics;
use App\Entity\Users;
use App\Repository\ArticlesRepository;
use App\Repository\TopicsRepository;
use App\Repository\UsersRepository;
use Doctrine\Common\Persistence\ObjectManager;
use http\Env\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin-articles", name="administration_article")
     */
    public function articlesAdmin (ArticlesRepository $articles){
        $articles = $articles->findAll();

        return $this->render("admin/articleAdministration.html.twig",[
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article-delete/{id}" ,name="article_delete")
     * @return Response
     */
        public function delArticle($id) {
            $entityManager = $this->getDoctrine()->getManager();
            $article = $entityManager->getRepository(Articles::class)->find($id);

            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash("success", "Ce message a ");
            return $this->redirectToRoute("home");
        }


    /**
     * @Route("/admin-users", name="administration_users")
     */
    public function usersAdmin (UsersRepository $users){
        $users = $users->findAll();

        return $this->render("admin/usersAdministration.html.twig",[
            'users' => $users,
        ]);
    }
    /**
     * @Route("/user-delete/{id}" ,name="user_delete")
     * @return Response
     */
    public function delUser($id){
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(Users::class)->find($id);

        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash("success", "Cet utilisateur a ");
        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/admin-forum", name="administration_forum")
     */
    public function forumAdmin (TopicsRepository $topics){
        $topics = $topics->findAll();

        return $this->render("admin/forumAdministration.html.twig",[
            'topics' => $topics,
        ]);
    }

    /**
     * @Route("/topic-delete/{id}" ,name="topic_delete")
     * @return Response
     */
    public function delTopic($id){
        $entityManager = $this->getDoctrine()->getManager();
        $topic = $entityManager->getRepository(Topics::class)->find($id);

        $entityManager->remove($topic);
        $entityManager->flush();
        $this->addFlash("success", "Ce topic a ");
        return $this->redirectToRoute("home");
    }

}

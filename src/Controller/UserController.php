<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Image;
use App\Entity\Users;
use App\Form\RegistrationType;
use App\Repository\UsersRepository;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/registration", name = "user_registration")
     */
    public function registration(Request $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder){

        $user = new Users();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            /** @var Image $image */
            $image = $user->getImage();

            /** @var UploadedFile $file */
            $file = $image->getFile();
            $name = md5(uniqid()). '.' .$file->guessExtension();
            $file->move("../public/img/uploaded-img/user-img", $name);
            $image->setName($name);

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setCreatedAt(new \DateTime());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute("user_login");
        }

        return $this->render('user/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="user_login")
     */
    public function login(){
        return $this->render("user/login.html.twig");
    }


    /**
     * @Route("/logout", name="user_logout")
     */
    public function logout(){

    }

    /**
     * @Route("/user/{id}", name="user_profile")
     */
    public function profilePage(Users $user){

//        $article = $repo->findById($user->getId());

        return $this->render("user/userProfile.html.twig",[
           'user' => $user,
//            'article' => $article,
        ]);
    }
}

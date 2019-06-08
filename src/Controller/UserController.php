<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Image;
use App\Entity\Users;
use App\Form\RegistrationType;
use App\Repository\ArticlesRepository;
use App\Repository\UsersRepository;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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
            $user->setRole(['ROLE_ADMIN']);

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
    public function profilePage(Users $user, ArticlesRepository $articlesRepository){

        $articles =  $articlesRepository->FindAllArticles($user,5);
        dd($articles);

        return $this->render("user/userProfile.html.twig",[
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user-promot/{id}",name="user_promotion")
     *
     * @param Users $users
     * @param ObjectManager $manager
     * @return RedirectResponse
     */
    public function adminPromotion(Users $users, ObjectManager $manager){
        if ($users->getRoles() === ['ROLE_USER']){
            $users->setRole(['ROLE_ADMIN']);
        }else{
            $users->setRole(['ROLE_USER']);
        }
        $manager->persist($users);
        $manager->flush();

        return $this->redirectToRoute("administration_users");
    }
    /**
     * @Route("/forgotten_password", name="app_forgotten_password")
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @return Response
     */
    public function forgottenPassword(
        Request $request,
        \Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator
    ): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(Users::class)->findOneByEmail($email);
            /* @var $user Users */
            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('home');
            }
            $token = $tokenGenerator->generateToken();
            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('home');
            }
            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
            $message = (new \Swift_Message('Forgot Password'))
                ->setFrom('g.ponty@dev-web.io')
                ->setTo($user->getEmail())
                ->setBody(
                    "Suivez le lien pour reset votre mot de passe : " . $url,
                    'text/html'
                );
            $mailer->send($message);
            $this->addFlash('notice', 'Mail envoyé');
            return $this->redirectToRoute('home');
        }
        return $this->render('user/forgotten_password.html.twig');
    }
    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(Users::class)->findOneByResetToken($token);
            /* @var $user Users */
            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('home');
            }
            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager->flush();
            $this->addFlash('notice', 'Mot de passe mis à jour');
            return $this->redirectToRoute('home');
        }else {
            return $this->render('user/reset_password.html.twig', ['token' => $token]);
        }
    }
}

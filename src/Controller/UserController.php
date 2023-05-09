<?php

namespace App\Controller;

use App\Entity\SignIn;
use App\Entity\User;
use App\Form\SignInType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,Request $request,SessionInterface $session): Response
    {
        
        $id =$session->get('id');
        if ($id!= null){
            $user = $entityManager
            ->getRepository(User::class)->find($id);
            if ($user->getType()=='admin'){
                $users = $entityManager
                ->getRepository(User::class)
                ->findAll();
    
            return $this->render('user/index.html.twig', [
                'users' => $users,
            ]);
            }else{
                return $this->redirectToRoute('eventsFront', [], Response::HTTP_SEE_OTHER);

            }
        }
        else{
                return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);

            }
     
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setType("admin");
            $user->setSoldepoint(0);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/front/signin', name: 'app_user_signin', methods: ['GET', 'POST'])]
    public function signin(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {

        $user = new SignIn();
        $form = $this->createForm(SignInType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $session->start();
            $connectedUser = $entityManager
            ->getRepository(User::class)
            ->findOneBy(array('adrmail' => $user->getAdrmail(),'mdp'=> $user->getMdp()));
            if ($connectedUser!=null){
                $session->set('id', $connectedUser->getId());
                if ($connectedUser->getType()=='admin'){
                    return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    
                }else if ($connectedUser->getType()=='client'){
                    //TODO Change when front end integrated
                    return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
                };
            }

        }

        return $this->renderForm('user/signin.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/front/signup', name: 'app_user_signup', methods: ['GET', 'POST'])]
    public function signup(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $user->setType("client");
            $user->setSoldepoint(0);
            $entityManager->persist($user);
            $entityManager->flush();
            $transport = (new EsmtpTransport('smtp.gmail.com', 587))
            ->setUsername('beeblyinfo@gmail.com')
            ->setPassword('ojqdadkqhwvefatr');
        
        // Create the Mailer instance
        $mailer = new Mailer($transport);
        
        // Create the email
        $email = (new Email())
            ->from('beeblyinfo@gmail.com')
            ->to('beeblyinfo@gmail.com')
            ->subject('Subject of the email')
            ->text('Plain text content of the email')
            ->html('<p>HTML content of the email</p>');
        // Send the email
        $mailer->send($email);

            return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('user/signup.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/back/profile', name: 'app_user_profile', methods: ['GET', 'POST'])]
    public function profile(Request $request, EntityManagerInterface $entityManager,SessionInterface $session,MailerInterface $mailer): Response
    {
        //TODO NJIB LUSER
        $user = new User();
        $id = $session->get('id');
        $user = $entityManager
        ->getRepository(User::class)->find($id);
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
         

         

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/profile.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/back/deleteAccount', name: 'app_user_deleteAccount', methods: ['GET', 'POST'])]
    public function deleteAccount(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $id = $session->get('id');
        $user = $entityManager
        ->getRepository(User::class)->find($id);
        
        
            $entityManager->remove($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
      

       
    }

    public function sendEmail(MailerInterface $mailer,User $user)
    {
        $email = (new Email())
            ->from('beeblyinfo@gmail.com')
            ->to('beeblyinfo@gmail.com')
            ->subject('Test email')
            ->text('This is a test email sent from Symfony 5.');

        $mailer->send($email);

        return new Response('Email sent!');
    }

    #[Route('/front/profile', name: 'app_user_profilefront', methods: ['GET', 'POST'])]
    public function profileFront(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        //TODO NJIB LUSER
        $user = new User();
        $id = $session->get('id');
        $user = $entityManager
        ->getRepository(User::class)->find($id);
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
         

         

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/profileFront.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
/*Mobile */
    #[Route('/api/usersApi', name: 'usersApi')]
    public function usersApi(Request $request,NormalizerInterface $normalizer): Response
    {

        $em = $this->getDoctrine()->getManager()->getRepository(User::class); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $users = $em->findAll(); // Select * from users;
        $jsonContent =$normalizer->normalize($users, 'json' ,['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    #[Route('/api/signinMobile', name: 'siginMobile')]
    public function siginMobile(NormalizerInterface $Normalizer,Request $request,EntityManagerInterface $entityManager): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $adrmail=$request->get('email');
        
        $password=$request->get('password');
        $user = $entityManager->getRepository(User::class)->findBy(['adrmail'=>$adrmail,'mdp'=>$password]);
        if ($user){

     
            $jsonContent = $Normalizer->normalize($user, 'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));
        }else{
            return new Response("failed");

        };
       

    }

    #[Route('/api/updateProfileMobile/{id}', name: 'updateProfileMobile')]
    public function updateProfileMobile($id,NormalizerInterface $Normalizer,Request $request,EntityManagerInterface $entityManager): Response
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->find($id);

        $user->setNom($request->get('nom'));
        $user->setPrenom($request->get('prenom'));
        $user->setAdrmail($request->get('adrmail'));
        $user->setMdp($request->get('mdp'));
        $user->setAdresse($request->get('adresse'));
        $user->setTel($request->get('tel'));
        $user->setCin($request->get('cin'));

        $em->persist($user);
        $em->flush();
            $jsonContent = $Normalizer->normalize($user, 'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));

    }

    #[Route('/api/signUpMobile', name: 'signUpMobile')]
    public function signUpMobile(NormalizerInterface $Normalizer,Request $request,EntityManagerInterface $entityManager): Response
    {
        $em = $this->getDoctrine()->getManager();

$user = new User();
        $user->setNom($request->get('nom'));
        $user->setPrenom($request->get('prenom'));
        $user->setAdrmail($request->get('adrmail'));
        $user->setMdp($request->get('mdp'));
        $user->setAdresse($request->get('adresse'));
        $user->setTel($request->get('tel'));
        $user->setCin($request->get('cin'));
        $user->setType("client");
        $user->setSoldepoint(0);
        $em->persist($user);
        $em->flush();
            $jsonContent = $Normalizer->normalize($user, 'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));

    }

    #[Route('/api/addAdminMobile', name: 'addAdminMobile')]
    public function addAdminMobile(NormalizerInterface $Normalizer,Request $request,EntityManagerInterface $entityManager): Response
    {
        $em = $this->getDoctrine()->getManager();

$user = new User();
        $user->setNom($request->get('nom'));
        $user->setPrenom($request->get('prenom'));
        $user->setAdrmail($request->get('adrmail'));
        $user->setMdp($request->get('mdp'));
        $user->setAdresse($request->get('adresse'));
        $user->setTel($request->get('tel'));
        $user->setCin($request->get('cin'));
        $user->setType("admin");
        $user->setSoldepoint(0);
        $em->persist($user);
        $em->flush();
            $jsonContent = $Normalizer->normalize($user, 'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));

    }

    #[Route('/api/deleteUserMobile/{id}', name: 'deleteUserMobile')]
    public function deleteUserMobile(Request $request,NormalizerInterface $normalizer,$id): Response
    {

        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
        $em = $this->getDoctrine()->getManager();

            $em->remove($user);
            $em->flush();
            $jsonContent =$normalizer->normalize($user, 'json' ,['groups'=>'post:read']);
            return new Response("information deleted successfully".json_encode($jsonContent));
    }
}

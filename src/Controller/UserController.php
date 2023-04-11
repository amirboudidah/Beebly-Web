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

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,Request $request,SessionInterface $session): Response
    {
        if($session!=null)
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
            }
        }
        else{
                return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);

            }

        }
     
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        if($session!=null)
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
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user,SessionInterface $session): Response
    {
        if($session!=null)
        {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
        }
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        if($session!=null)
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
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        if($session!=null)
        {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
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
                    return $this->redirectToRoute('app_user_profilefront', [], Response::HTTP_SEE_OTHER);
                };
            }

        }

        return $this->renderForm('user/signin.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/front/signup', name: 'app_user_signup', methods: ['GET', 'POST'])]
    public function signup(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {        if($session!=null)
        {
$user = new User();
$form = $this->createForm(UserType::class, $user);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {


    if ($user==null) {
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
    } else {
        $this->addFlash('warning', 'This email is already in use.');
    }
}  
        return $this->renderForm('user/signup.html.twig', [
            'form' => $form,
        ]);
    }
    }

    #[Route('/back/profile', name: 'app_user_profile', methods: ['GET', 'POST'])]
    public function profile(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {        if($session!=null)
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
    }

    #[Route('/back/deleteAccount', name: 'app_user_deleteAccount', methods: ['GET', 'POST'])]
    public function deleteAccount(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {        if($session!=null)
        {
        $id = $session->get('id');
        $user = $entityManager
        ->getRepository(User::class)->find($id);
        
        
            $entityManager->remove($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
      
        }
       
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

    #[Route('/log/out', name: 'app_user_logout', methods: ['GET', 'POST'])]
    public function logout(Request $request, EntityManagerInterface $entityManager,SessionInterface $session)
    {
        if($session!=null)
        {
            $session=null;
            return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
      
        }
    }

    #[Route('/front/profile', name: 'app_user_profilefront', methods: ['GET', 'POST'])]
    public function profileFront(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        if($session!=null)
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
         

         

            return $this->redirectToRoute('app_user_profilefront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/profileFront.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
}

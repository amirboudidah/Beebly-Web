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
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\JsonResponse;
#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager,Request $request,SessionInterface $session): Response
    {
        $user = new User();
        if($session->get('id')!=null)
        {
        $id =$session->get('id');
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
            else{
                $route = $request->headers->get('referer');
                return $this->redirect($route);

            }
        

        }    
        else{
            return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
        }
     
    }
    #[Route('/export/pdf', name: 'pdfUsers', methods: ['GET'])]
    public function pdfd (EntityManagerInterface $entityManager,Request $request): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
//        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

//        $produit = $produitRepository->findAll();

        // Retrieve the HTML generated in our twig file
        $data=$entityManager
        ->getRepository(User::class)
        ->findAll();
       
        $html = $this->renderView('user/pdf.html.twig',[
            'users' => $data,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Users.pdf", [
            "Attachment" => true
        ]);
        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    #[Route('/front/recSearch', name: 'recSearch', methods: ['GET','POST'])]
    public function search(Request $request, EntityManagerInterface $entityManager)
    {
        // Get search parameters from request
        $searchType = $request->query->get('searchType');
        $searchValue = $request->query->get('searchValue');
        
        // Query the database with search parameters using DQL
        $query = $entityManager->createQuery("SELECT t FROM App\Entity\User t WHERE t.$searchType LIKE :searchValue")
            ->setParameter('searchValue', '%' . $searchValue . '%');
        $users = $query->getResult();
       
         // Manually serialize entities to avoid circular references
         $serializedRecs = [];
         foreach ($users as $user) {
             $serializedRecs[] = [
                
                'id' => $user->getId(),
                 'adresse' => $user->getAdresse(),
                 'adrmail' => $user->getAdrmail(),
                 'cin' => $user->getCin(),
                 'nom' => $user->getNom(),
                 'prenom' => $user->getPrenom(),
                 'soldepoint' => $user->getSoldepoint(),
                 
                 'type' => $user->getType(),
                 'tel' => $user->getTel(),
                 
             ];
         }
            // Create JSON response
        $response = new JsonResponse();
        $response->setData(['users' => $serializedRecs]);
        return $response;
    }
    
    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $user = new User();
        if($session->get('id')!=null)
        {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $adrmail = $form["adrmail"]->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $existingUser = $entityManager
            ->getRepository(User::class)
            ->findOneBy(['adrmail' => $adrmail]);
        
            if ($existingUser==null) {
            $user->setType("admin");
            $user->setSoldepoint(0);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }}

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
        }    else{
            return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET','POST'])]
    public function show($id,Request $request, EntityManagerInterface $entityManager,User $user,SessionInterface $session): Response
    {
        $user = new User();
        if($session->get('id')!=null)
        {

            $user = $entityManager
            ->getRepository(User::class)->find($id);
         
                return $this->render('user/show.html.twig', [
                    'user' => $user,
                ]);
         
        }    
        else{
            return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        if($session->get('id')!=null)
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
        }    else{
            return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/delete/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        if($session->get('id')!=null)
        {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }    else{
        return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
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
    {        
$user = new User();
$form = $this->createForm(UserType::class, $user);
$form->handleRequest($request);
$adrmail = $form["adrmail"]->getData();
if ($form->isSubmitted() && $form->isValid()) {
    $existingUser = $entityManager
    ->getRepository(User::class)
    ->findOneBy(['adrmail' => $adrmail]);

    if ($existingUser==null) {
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
            ->to($adrmail)
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
            'user' => $user,
        ]);
    
    }

    #[Route('/back/profile', name: 'app_user_profile', methods: ['GET', 'POST'])]
    public function profile(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {        if($session->get('id')!=null)
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
            'mdp' => $user->getMdp(),
            
        ]);
    }else{return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);}
    }

    #[Route('/back/deleteAccount', name: 'app_user_deleteAccount', methods: ['GET', 'POST'])]
    public function deleteAccount(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {        if($session->get('id')!=null)
        {
        $id = $session->get('id');
        $user = $entityManager
        ->getRepository(User::class)->find($id);
        
        
            $entityManager->remove($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
      
        }    else{
            return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
        }
       
    }

    public function sendEmail(MailerInterface $mailer,User $user,string $email)
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
        if($session->get('id')!=null)
        {
            $session->invalidate();
            return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
      
        }else{return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);}
    }

    #[Route('/front/profile', name: 'app_user_profilefront', methods: ['GET', 'POST'])]
    public function profileFront(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        if($session->get('id')!=null)
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
    else{
        return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
    }
}
}

<?php

namespace App\Controller;

use App\Entity\Fgtpwd;
use App\Entity\SignIn;
use App\Entity\User;
use App\Form\NewpwdType;
use App\Form\SignInType;
use App\Form\FgtpwdType;
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

#[Route('/front/fgtpwd', name: 'app_user_fgtpwd', methods: ['GET', 'POST'])]
public function fgtpwd(Request $request, EntityManagerInterface $entityManager)
{
    $user = new Fgtpwd();
    $form = $this->createForm(FgtpwdType::class, $user);
    $form->handleRequest($request);
    $use= new User();
    if ($form->isSubmitted() && $form->isValid()) {
        $connectedUser = $entityManager
        ->getRepository(User::class)
        ->findOneBy(array('adrmail' => $user->getAdrmail()));
        $use=$connectedUser;
        if ($connectedUser!=null){
            $adrmail = $form["adrmail"]->getData();
            $transport = (new EsmtpTransport('smtp.gmail.com', 587))
            ->setUsername('beeblyinfo@gmail.com')
            ->setPassword('ojqdadkqhwvefatr');

        // Create the Mailer instance
        $mailer = new Mailer($transport);

        // Create the email
        $email = (new Email())
            ->from('beeblyinfo@gmail.com')
            ->to($adrmail)
            ->subject('change password')
            ->text('Plain text content of the email')
            ->html('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="font-family:Poppins, sans-serif">
             <head>
              <meta charset="UTF-8">
              <meta content="width=device-width, initial-scale=1" name="viewport">
              <meta name="x-apple-disable-message-reformatting">
              <meta http-equiv="X-UA-Compatible" content="IE=edge">
              <meta content="telephone=no" name="format-detection">
              <title>New message</title><!--[if (mso 16)]>
                <style type="text/css">
                a {text-decoration: none;}
                </style>
                <![endif]--><!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--><!--[if gte mso 9]>
            <xml>
                <o:OfficeDocumentSettings>
                <o:AllowPNG></o:AllowPNG>
                <o:PixelsPerInch>96</o:PixelsPerInch>
                </o:OfficeDocumentSettings>
            </xml>
            <![endif]--><!--[if !mso]><!-- -->
              <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet"><!--<![endif]-->
              <style type="text/css">
            #outlook a {
                padding:0;
            }
            .es-button {
                mso-style-priority:100!important;
                text-decoration:none!important;
            }
            a[x-apple-data-detectors] {
                color:inherit!important;
                text-decoration:none!important;
                font-size:inherit!important;
                font-family:inherit!important;
                font-weight:inherit!important;
                line-height:inherit!important;
            }
            .es-desk-hidden {
                display:none;
                float:left;
                overflow:hidden;
                width:0;
                max-height:0;
                line-height:0;
                mso-hide:all;
            }
            @media only screen and (max-width:600px) {p, ul li, ol li, a { line-height:150%!important } h1, h2, h3, h1 a, h2 a, h3 a { line-height:120% } h1 { font-size:50px!important; text-align:center } h2 { font-size:36px!important; text-align:left } h3 { font-size:20px!important; text-align:left } .es-header-body h1 a, .es-content-body h1 a, .es-footer-body h1 a { font-size:50px!important; text-align:center } .es-header-body h2 a, .es-content-body h2 a, .es-footer-body h2 a { font-size:36px!important; text-align:left } .es-header-body h3 a, .es-content-body h3 a, .es-footer-body h3 a { font-size:20px!important; text-align:left } .es-menu td a { font-size:12px!important } .es-header-body p, .es-header-body ul li, .es-header-body ol li, .es-header-body a { font-size:14px!important } .es-content-body p, .es-content-body ul li, .es-content-body ol li, .es-content-body a { font-size:14px!important } .es-footer-body p, .es-footer-body ul li, .es-footer-body ol li, .es-footer-body a { font-size:12px!important } .es-infoblock p, .es-infoblock ul li, .es-infoblock ol li, .es-infoblock a { font-size:12px!important } *[class="gmail-fix"] { display:none!important } .es-m-txt-c, .es-m-txt-c h1, .es-m-txt-c h2, .es-m-txt-c h3 { text-align:center!important } .es-m-txt-r, .es-m-txt-r h1, .es-m-txt-r h2, .es-m-txt-r h3 { text-align:right!important } .es-m-txt-l, .es-m-txt-l h1, .es-m-txt-l h2, .es-m-txt-l h3 { text-align:left!important } .es-m-txt-r img, .es-m-txt-c img, .es-m-txt-l img { display:inline!important } .es-button-border { display:inline-block!important } a.es-button, button.es-button { font-size:18px!important; display:inline-block!important } .es-adaptive table, .es-left, .es-right { width:100%!important } .es-content table, .es-header table, .es-footer table, .es-content, .es-footer, .es-header { width:100%!important; max-width:600px!important } .es-adapt-td { display:block!important; width:100%!important } .adapt-img { width:100%!important; height:auto!important } .es-m-p0 { padding:0!important } .es-m-p0r { padding-right:0!important } .es-m-p0l { padding-left:0!important } .es-m-p0t { padding-top:0!important } .es-m-p0b { padding-bottom:0!important } .es-m-p20b { padding-bottom:20px!important } .es-mobile-hidden, .es-hidden { display:none!important } tr.es-desk-hidden, td.es-desk-hidden, table.es-desk-hidden { width:auto!important; overflow:visible!important; float:none!important; max-height:inherit!important; line-height:inherit!important } tr.es-desk-hidden { display:table-row!important } table.es-desk-hidden { display:table!important } td.es-desk-menu-hidden { display:table-cell!important } .es-menu td { width:1%!important } table.es-table-not-adapt, .esd-block-html table { width:auto!important } table.es-social { display:inline-block!important } table.es-social td { display:inline-block!important } .es-m-p5 { padding:5px!important } .es-m-p5t { padding-top:5px!important } .es-m-p5b { padding-bottom:5px!important } .es-m-p5r { padding-right:5px!important } .es-m-p5l { padding-left:5px!important } .es-m-p10 { padding:10px!important } .es-m-p10t { padding-top:10px!important } .es-m-p10b { padding-bottom:10px!important } .es-m-p10r { padding-right:10px!important } .es-m-p10l { padding-left:10px!important } .es-m-p15 { padding:15px!important } .es-m-p15t { padding-top:15px!important } .es-m-p15b { padding-bottom:15px!important } .es-m-p15r { padding-right:15px!important } .es-m-p15l { padding-left:15px!important } .es-m-p20 { padding:20px!important } .es-m-p20t { padding-top:20px!important } .es-m-p20r { padding-right:20px!important } .es-m-p20l { padding-left:20px!important } .es-m-p25 { padding:25px!important } .es-m-p25t { padding-top:25px!important } .es-m-p25b { padding-bottom:25px!important } .es-m-p25r { padding-right:25px!important } .es-m-p25l { padding-left:25px!important } .es-m-p30 { padding:30px!important } .es-m-p30t { padding-top:30px!important } .es-m-p30b { padding-bottom:30px!important } .es-m-p30r { padding-right:30px!important } .es-m-p30l { padding-left:30px!important } .es-m-p35 { padding:35px!important } .es-m-p35t { padding-top:35px!important } .es-m-p35b { padding-bottom:35px!important } .es-m-p35r { padding-right:35px!important } .es-m-p35l { padding-left:35px!important } .es-m-p40 { padding:40px!important } .es-m-p40t { padding-top:40px!important } .es-m-p40b { padding-bottom:40px!important } .es-m-p40r { padding-right:40px!important } .es-m-p40l { padding-left:40px!important } .es-desk-hidden { display:table-row!important; width:auto!important; overflow:visible!important; max-height:inherit!important } }
            </style>
             </head>
             <body style="width:100%;font-family:Poppins, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">
              <div class="es-wrapper-color" style="background-color:#EDF2F4"><!--[if gte mso 9]>
                        <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
                            <v:fill type="tile" color="#edf2f4"></v:fill>
                        </v:background>
                    <![endif]-->
               <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;background-color:#EDF2F4">
                 <tr>
                  <td valign="top" style="padding:0;Margin:0">
                   <table cellpadding="0" cellspacing="0" class="es-header" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top">
                     <tr>
                      <td align="center" style="padding:0;Margin:0">
                       <table bgcolor="#ffffff" class="es-header-body" align="center" cellpadding="0" cellspacing="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">
                         <tr>
                          <td align="left" style="Margin:0;padding-bottom:10px;padding-top:20px;padding-left:20px;padding-right:20px"><!--[if mso]><table style="width:560px" cellpadding="0" cellspacing="0"><tr><td style="width:270px" valign="top"><![endif]-->
                           <table cellpadding="0" cellspacing="0" align="left" class="es-left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left">
                             <tr>
                              <td align="left" class="es-m-p20b" style="padding:0;Margin:0;width:270px">
                               <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                 <tr>
                                  <td align="left" class="es-m-txt-c" style="padding:0;Margin:0;font-size:0px"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#8D99AE;font-size:14px"><img src="https://gwsitb.stripocdn.email/content/guids/CABINET_33d4c597b27166156b65a6fa668bfbdf/images/group.png" alt="Logo" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic" title="Logo" height="50"></a></td>
                                 </tr>
                               </table></td>
                             </tr>
                           </table><!--[if mso]></td><td style="width:20px"></td><td style="width:270px" valign="top"><![endif]-->
                           <table cellpadding="0" cellspacing="0" class="es-right" align="right" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right">
                             <tr>
                              <td align="left" style="padding:0;Margin:0;width:270px">
                               <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                 <tr>
                                  <td align="right" class="es-m-txt-c es-m-p0t" style="padding:0;Margin:0;padding-top:30px"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:Poppins, sans-serif;line-height:21px;color:#8D99AE;font-size:14px"><b>August 24</b></p></td>
                                 </tr>
                               </table></td>
                             </tr>
                           </table><!--[if mso]></td></tr></table><![endif]--></td>
                         </tr>
                         <tr>
                          <td align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px">
                           <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                             <tr>
                              <td align="center" valign="top" style="padding:0;Margin:0;width:560px">
                               <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                 <tr>
                                  <td align="center" style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px;font-size:0">
                                   <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                     <tr>
                                      <td style="padding:0;Margin:0;border-bottom:1px solid #8d99ae;background:unset;height:1px;width:100%;margin:0px"></td>
                                     </tr>
                                   </table></td>
                                 </tr>
                               </table></td>
                             </tr>
                           </table></td>
                         </tr>
                         <tr>
                          <td align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px"><!--[if mso]><table style="width:560px" cellpadding="0" cellspacing="0"><tr><td style="width:326px" valign="top"><![endif]-->
                           <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left">
                             <tr>
                              <td class="es-m-p20b" align="left" style="padding:0;Margin:0;width:326px">
                               <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                 <tr>
                                  <td style="padding:0;Margin:0">
                                   <table cellpadding="0" cellspacing="0" width="100%" class="es-menu" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                     <tr class="links">
                                      <td align="center" valign="top" width="25%" id="esd-menu-id-0" style="Margin:0;padding-left:5px;padding-right:5px;padding-top:8px;padding-bottom:6px;border:0"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family:Poppins, sans-serif;color:#8D99AE;font-size:12px;font-weight:normal">RECIPES</a></td>
                                      <td align="center" valign="top" width="25%" id="esd-menu-id-1" style="Margin:0;padding-left:5px;padding-right:5px;padding-top:8px;padding-bottom:6px;border:0"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family:Poppins, sans-serif;color:#8D99AE;font-size:12px;font-weight:normal"> FORUM</a></td>
                                      <td align="center" valign="top" width="25%" id="esd-menu-id-2" style="Margin:0;padding-left:5px;padding-right:5px;padding-top:8px;padding-bottom:6px;border:0"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family:Poppins, sans-serif;color:#8D99AE;font-size:12px;font-weight:normal"> SHOP </a></td>
                                      <td align="center" valign="top" width="25%" id="esd-menu-id-2" style="Margin:0;padding-left:5px;padding-right:5px;padding-top:8px;padding-bottom:6px;border:0"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family:Poppins, sans-serif;color:#8D99AE;font-size:12px;font-weight:normal">ABOUT</a></td>
                                     </tr>
                                   </table></td>
                                 </tr>
                               </table></td>
                             </tr>
                           </table><!--[if mso]></td><td style="width:20px"></td><td style="width:214px" valign="top"><![endif]-->
                           <table cellpadding="0" cellspacing="0" class="es-right" align="right" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right">
                             <tr>
                              <td align="left" style="padding:0;Margin:0;width:214px">
                               <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                 <tr>
                                  <td align="right" class="es-m-txt-c" style="padding:0;Margin:0;padding-top:5px;padding-bottom:5px;font-size:0">
                                   <table cellpadding="0" cellspacing="0" class="es-table-not-adapt es-social" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                     <tr>
                                      <td align="center" valign="top" style="padding:0;Margin:0;padding-right:10px"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#8D99AE;font-size:14px"><img title="Facebook" src="https://gwsitb.stripocdn.email/content/guids/CABINET_33d4c597b27166156b65a6fa668bfbdf/images/facebook_14.png" alt="Fb" height="24" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td>
                                      <td align="center" valign="top" style="padding:0;Margin:0;padding-right:10px"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#8D99AE;font-size:14px"><img title="Twitter" src="https://gwsitb.stripocdn.email/content/guids/CABINET_33d4c597b27166156b65a6fa668bfbdf/images/twitter_4.png" alt="Tw" height="24" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td>
                                      <td align="center" valign="top" style="padding:0;Margin:0;padding-right:10px"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#8D99AE;font-size:14px"><img title="Instagram" src="https://gwsitb.stripocdn.email/content/guids/CABINET_33d4c597b27166156b65a6fa668bfbdf/images/twitter_6.png" alt="Inst" height="24" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td>
                                      <td align="center" valign="top" style="padding:0;Margin:0"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#8D99AE;font-size:14px"><img title="Pinterest" src="https://gwsitb.stripocdn.email/content/guids/CABINET_33d4c597b27166156b65a6fa668bfbdf/images/twitter_5.png" alt="P" height="24" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td>
                                     </tr>
                                   </table></td>
                                 </tr>
                               </table></td>
                             </tr>
                           </table><!--[if mso]></td></tr></table><![endif]--></td>
                         </tr>
                         <tr>
                          <td align="left" style="padding:0;Margin:0;padding-bottom:10px;padding-left:20px;padding-right:20px">
                           <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                             <tr>
                              <td align="center" valign="top" style="padding:0;Margin:0;width:560px">
                               <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                 <tr>
                                  <td align="center" style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px;font-size:0">
                                   <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                     <tr>
                                      <td style="padding:0;Margin:0;border-bottom:1px solid #8d99ae;background:unset;height:1px;width:100%;margin:0px"></td>
                                     </tr>
                                   </table></td>
                                 </tr>
                               </table></td>
                             </tr>
                           </table></td>
                         </tr>
                       </table></td>
                     </tr>
                   </table>
                   <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
                     <tr>
                      <td align="center" style="padding:0;Margin:0">
                       <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">
                         <tr>
                          <td class="es-m-p20r es-m-p20l" align="left" style="Margin:0;padding-top:25px;padding-bottom:25px;padding-left:40px;padding-right:40px">
                           <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                             <tr>
                              <td align="left" style="padding:0;Margin:0;width:520px">
                               <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-radius:25px;background-color:#e0ecfe" bgcolor="#e0ecfe" role="presentation">
                                 <tr>
                                  <td align="center" style="padding:0;Margin:0;padding-top:20px;font-size:0px"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#8D99AE;font-size:14px"><img src="https://gwsitb.stripocdn.email/content/guids/CABINET_c9caacaf71bd41e65369951f9007184063f0b585518ef1531165f4ac2deaa9a1/images/untitled1.png" alt style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic" width="60"></a></td>
                                 </tr>
                                 <tr>
                                  <td align="center" class="es-m-txt-c" style="Margin:0;padding-top:10px;padding-bottom:20px;padding-left:25px;padding-right:25px"><h2 style="Margin:0;line-height:43px;mso-line-height-rule:exactly;font-family:Poppins, sans-serif;font-size:36px;font-style:normal;font-weight:bold;color:#2B2D42">chnage password</h2></td>
                                 </tr>
                                 <tr>
                                  <td align="center" class="es-m-txt-c" style="padding:0;Margin:0;padding-bottom:20px;padding-left:25px;padding-right:25px"><h3 style="Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:Poppins, sans-serif;font-size:20px;font-style:normal;font-weight:bold;color:#8D99AE">Privacy Notice Update</h3></td>
                                 </tr>
                                 <tr>
                                  <td align="left" style="padding:0;Margin:0;padding-bottom:10px;padding-left:25px;padding-right:25px"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:Poppins, sans-serif;line-height:21px;color:#8d99ae;font-size:14px">here is the link to change your password:<br><strong><br>This update will be effective immidiatly</strong></p></td>
                                 </tr>
                                 <tr>
                                  <td align="center" style="padding:0;Margin:0;padding-top:20px;padding-bottom:20px;font-size:0">
                                   <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                     <tr>
                                      <td style="padding:0;Margin:0;border-bottom:1px solid #c3cedf;background:unset;height:1px;width:100%;margin:0px"></td>
                                     </tr>
                                   </table></td>
                                 </tr>
                                 <tr>
                                  <td align="center" style="padding:0;Margin:0;padding-bottom:20px"><!--[if mso]><a href="https://viewstripo.email" target="_blank" hidden>
                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" esdevVmlButton href="https://viewstripo.email" 
                            style="height:24px; v-text-anchor:middle; width:83px" arcsize="50%" stroke="f"  fillcolor="#e0ecfe">
                    <w:anchorlock></w:anchorlock>
                    <center style='.'color:#60b9d8; font-family:Poppins, sans-serif; font-size:8px; font-weight:400; line-height:8px;  mso-text-raise:1px'.'>link</center>
                </v:roundrect></a>
            <![endif]--><!--[if !mso]><!-- --><span class="es-button-border msohide" style="border-style:solid;border-color:#2CB543;background:#e0ecfe;border-width:0px;display:inline-block;border-radius:30px;width:auto;mso-border-alt:10px;mso-hide:all"><a href="http://localhost:8000/user/front/'.$use->getId().'/newpwd" class="es-button es-button-1680784515156" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#60b9d8;font-size:18px;display:inline-block;background:#e0ecfe;border-radius:30px;font-family:Poppins, sans-serif;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center;padding:0px 20px;border-color:#e0ecfe">change password</a></span><!--<![endif]--></td>
                                 </tr>
                               </table></td>
                             </tr>
                           </table></td>
                         </tr>
                       </table></td>
                     </tr>
                   </table>
                   <table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top">
                     <tr>
                      <td align="center" style="padding:0;Margin:0">
                       <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">
                         <tr>
                          <td align="left" style="Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;padding-bottom:30px">
                           <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                             <tr>
                              <td align="left" style="padding:0;Margin:0;width:560px">
                               <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                 <tr>
                                  <td style="padding:0;Margin:0">
                                   <table cellpadding="0" cellspacing="0" width="100%" class="es-menu" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                     <tr class="links">
                                      <td align="center" valign="top" width="25%" style="Margin:0;padding-left:5px;padding-right:5px;padding-top:10px;padding-bottom:10px;border:0"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family:Poppins, sans-serif;color:#8D99AE;font-size:12px;font-weight:normal">ABOUT US</a></td>
                                      <td align="center" valign="top" width="25%" style="Margin:0;padding-left:5px;padding-right:5px;padding-top:10px;padding-bottom:10px;border:0"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family:Poppins, sans-serif;color:#8D99AE;font-size:12px;font-weight:normal">NEWS</a></td>
                                      <td align="center" valign="top" width="25%" style="Margin:0;padding-left:5px;padding-right:5px;padding-top:10px;padding-bottom:10px;border:0"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family:Poppins, sans-serif;color:#8D99AE;font-size:12px;font-weight:normal">CAREER</a></td>
                                      <td align="center" valign="top" width="25%" style="Margin:0;padding-left:5px;padding-right:5px;padding-top:10px;padding-bottom:10px;border:0"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family:Poppins, sans-serif;color:#8D99AE;font-size:12px;font-weight:normal">THE SHOPS</a></td>
                                     </tr>
                                   </table></td>
                                 </tr>
                                 <tr>
                                  <td align="center" class="es-m-txt-c" style="padding:0;Margin:0;padding-top:5px;padding-bottom:5px;font-size:0">
                                   <table cellpadding="0" cellspacing="0" class="es-table-not-adapt es-social" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                     <tr>
                                      <td align="center" valign="top" style="padding:0;Margin:0;padding-right:10px"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#8D99AE;font-size:12px"><img title="Facebook" src="https://gwsitb.stripocdn.email/content/guids/CABINET_33d4c597b27166156b65a6fa668bfbdf/images/facebook_14.png" alt="Fb" height="24" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td>
                                      <td align="center" valign="top" style="padding:0;Margin:0;padding-right:10px"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#8D99AE;font-size:12px"><img title="Twitter" src="https://gwsitb.stripocdn.email/content/guids/CABINET_33d4c597b27166156b65a6fa668bfbdf/images/twitter_4.png" alt="Tw" height="24" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td>
                                      <td align="center" valign="top" style="padding:0;Margin:0;padding-right:10px"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#8D99AE;font-size:12px"><img title="Instagram" src="https://gwsitb.stripocdn.email/content/guids/CABINET_33d4c597b27166156b65a6fa668bfbdf/images/twitter_6.png" alt="Inst" height="24" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td>
                                      <td align="center" valign="top" style="padding:0;Margin:0"><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#8D99AE;font-size:12px"><img title="Pinterest" src="https://gwsitb.stripocdn.email/content/guids/CABINET_33d4c597b27166156b65a6fa668bfbdf/images/twitter_5.png" alt="P" height="24" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td>
                                     </tr>
                                   </table></td>
                                 </tr>
                                 <tr>
                                  <td align="center" style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:Poppins, sans-serif;line-height:18px;color:#8D99AE;font-size:12px">You are receiving this email because you have visited our site or asked us about the regular newsletter. Make sure our messages get to your Inbox (and not your bulk or junk folders).<br><strong><a target="_blank" href="https://viewstripo.email" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#8D99AE;font-size:12px">Privacy police</a> | <a target="_blank" href="" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#8D99AE;font-size:12px">Unsubscribe</a></strong></p></td>
                                 </tr>
                               </table></td>
                             </tr>
                           </table></td>
                         </tr>
                         <tr>
                          <td align="left" style="padding:20px;Margin:0">
                           <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                             <tr>
                              <td align="left" style="padding:0;Margin:0;width:560px">
                               <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                 <tr>
                                  <td align="center" class="made_with" style="padding:0;Margin:0;font-size:0"><a target="_blank" href="https://viewstripo.email/?utm_source=templates&utm_medium=email&utm_campaign=good_food_2&utm_content=privacy_notice_update" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#8D99AE;font-size:12px"><img src="https://gwsitb.stripocdn.email/content/guids/CABINET_09023af45624943febfa123c229a060b/images/7911561025989373.png" alt width="125" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></a></td>
                                 </tr>
                               </table></td>
                             </tr>
                           </table></td>
                         </tr>
                       </table></td>
                     </tr>
                   </table></td>
                 </tr>
               </table>
              </div>
             </body>
            </html>');

        // Send the email
        $mailer->send($email);
                return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
            }else{
                return $this->redirectToRoute('app_user_fgtpwd', [], Response::HTTP_SEE_OTHER);
            };
        }

    

    return $this->renderForm('user/fgtpwd.html.twig', [
        'form' => $form,
    ]);
}

#[Route('/front/{id}/newpwd', name: 'app_user_newpwd', methods: ['GET', 'POST'])]
public function newpassword(Request $request, EntityManagerInterface $entityManager,$id, User $user)
{   $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);
    $us = $entityManager
    ->getRepository(User::class)->find($id);
    $pwd=$form["mdp"]->getData();
    if ($form->isSubmitted() && $form->isValid()) {
        $user->setMdp($pwd);
        $entityManager->flush();
        return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);

    }

    return $this->renderForm('user/newpwd.html.twig', [
        'user' => $us,
        'form' => $form,
    ]);

}

}

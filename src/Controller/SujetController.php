<?php

namespace App\Controller;
use App\Entity\User;

use App\Entity\Commentaire;
use App\Entity\Topic;
use App\Entity\Sujet;
use App\Form\SujetType;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/sujet')]
class SujetController extends AbstractController
{
    #[Route('/', name: 'app_sujet_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $sujets = $entityManager
            ->getRepository(Sujet::class)
            ->findAll();

        return $this->render('sujet/index.html.twig', [
            'sujets' => $sujets,
        ]);
    }

    #[Route('/new/{idtopic}', name: 'app_sujet_new', methods: ['GET', 'POST'])]
    public function new($idtopic,Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $sujet = new Sujet();
        $id = $session->get('id');
        $user = $entityManager
        ->getRepository(User::class)->find($id);
        
        $topic = $entityManager
            ->getRepository(Topic::class)
            ->find($idtopic);
      
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sujet->setAccepter(0);
            $sujet->setNbcom(0);
            $sujet->setIdtopic($topic);
            $sujet->setIduser($user);
            $entityManager->persist($sujet);
            $entityManager->flush();

            return $this->redirectToRoute('topicFront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sujet/new.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
        ]);
    }

    #[Route('/{idsujet}', name: 'app_sujet_show', methods: ['GET'])]
    public function show(Sujet $sujet,EntityManagerInterface $entityManager): Response
    {
        $commentaires = $entityManager
        ->getRepository(Commentaire::class)
        ->findBy(['idsujet' => $sujet]);
       
        return $this->render('sujet/show.html.twig', [
            'sujet' => $sujet,
            'commentaire' => $commentaires
        ]);
    }

    #[Route('/frontSujet/{idsujet}', name: 'frontSujet', methods: ['GET', 'POST'])]
    public function frontSujet(MailerInterface $maileer,Request $request,Sujet $sujet,EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        $id = $session->get('id');
        $user = $entityManager
        ->getRepository(User::class)->find($id);
        
        $commentaires = $entityManager
        ->getRepository(Commentaire::class)
        ->findBy(['idsujet' => $sujet]);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setIdsujet($sujet);
            $commentaire->setIduser($user);
            $commentaire->setNbdislike(0);
            $commentaire->setNblike(0);
            $containsBadWord = $this->checkBadWord($commentaire->getContenu());

        if ($containsBadWord) {
            // Do something with the bad word
           
            

            $transport = Transport::fromDsn("smtp://pidev.beebly@gmail.com:bwukvzhiqbpwyrdc@smtp.gmail.com:587?encryption=tls");
            $mailer = new Mailer($transport);
           $emailTo = "pidev.beebly@gmail.com";//$terrain->getIdPartenaire()->getEmail() ;
            $email = (new Email())
       
            ->from('pidev.beebly@gmail.com')
            ->to($emailTo)
            ->subject('Bad Word Notification!')
            ->text('Sending emails is fun again!')
            ->html('
            
                <h1>Bad Word Notification</h1>
                <p>Dear'. $user->getNom() .',</p>
                <p>We have detected that you used a bad word in your message on our website. This is not allowed as per our community guidelines.</p>
                <p>Please refrain from using inappropriate language in your future messages.</p>
                <p>Thank you for your cooperation.</p>
                <p>Sincerely,<br>Beebly Team</p>
           ');   
        

$headers = $email->getHeaders();

$mailer->send($email);

        } else {
            
            $entityManager->persist($commentaire);
            $entityManager->flush();
        }


            return $this->redirectToRoute('topicFront', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('sujet/frontSujet.html.twig', [
            'form' => $form->createView(),
            'commentaire' => $commentaire,
            'sujet' => $sujet,
            'commentaires' => $commentaires
        ]);
    }

    #[Route('/{idsujet}/edit', name: 'app_sujet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sujet $sujet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sujet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sujet/edit.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
        ]);
    }

    #[Route('/{idsujet}', name: 'app_sujet_delete', methods: ['POST'])]
    public function delete(Request $request, Sujet $sujet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sujet->getIdsujet(), $request->request->get('_token'))) {
            $entityManager->remove($sujet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sujet_index', [], Response::HTTP_SEE_OTHER);
    }

    public function checkBadWord(string $word): bool
    {
        $badWords = ['Badword1', 'Badword2', 'Badword3']; 

        foreach ($badWords as $badWord) {
            if (stripos($word, $badWord) !== false) {
                return true;
            }
        }

        return false;
    }

}

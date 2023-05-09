<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Entity\Sujet;
use App\Entity\User;
use App\Form\TopicType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
#[Route('/topic')]
class TopicController extends AbstractController
{
    #[Route('/', name: 'app_topic_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $topics = $entityManager
            ->getRepository(Topic::class)
            ->findAll();

        return $this->render('topic/index.html.twig', [
            'topics' => $topics,
        ]);
    }

    #[Route('/front', name: 'topicFront', methods: ['GET'])]
    public function topicFront(EntityManagerInterface $entityManager): Response
    {
        $topics = $entityManager
            ->getRepository(Topic::class)
            ->findAll();

        return $this->render('topic/indexFront.html.twig', [
            'topics' => $topics,
        ]);
    }

    #[Route('/new', name: 'app_topic_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $id =$session->get('id');
        if ($id!= null){
            $user = $entityManager
            ->getRepository(User::class)
            ->find($id);
        $topic = new Topic();
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
        $topic->setIduser($user);
        $topic->setHide(0);
        $topic->setNbsujet(0);
        $topic->setAccepter(0);
            $entityManager->persist($topic);
            $entityManager->flush();

            return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('topic/new.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }else{
        return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);

    }
    }
    #[Route('/front/{idtopic}', name: 'showFront', methods: ['GET'])]
    public function showTopicAndSubjectsFront(EntityManagerInterface $entityManager,Topic $topic): Response
    {
        $sujets = $entityManager
            ->getRepository(Sujet::class)
            ->findBy(['idtopic' => $topic->getIdtopic()]);
        //dd($sujets);
        return $this->render('topic/showFront.html.twig', [
            'topic' => $topic,
            'sujets' => $sujets
        ]);
    }


    #[Route('/{idtopic}', name: 'app_topic_show', methods: ['GET'])]
    public function show(Topic $topic): Response
    {
        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
        ]);
    }

    #[Route('/{idtopic}/edit', name: 'app_topic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('topic/edit.html.twig', [
            'topic' => $topic,
            'form' => $form,
        ]);
    }

    #[Route('/{idtopic}', name: 'app_topic_delete', methods: ['POST'])]
    public function delete(Request $request, Topic $topic, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$topic->getIdtopic(), $request->request->get('_token'))) {
            $entityManager->remove($topic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_topic_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/api/topicApi', name: 'topicApi')]
    public function topicApi(Request $request,NormalizerInterface $normalizer): Response
    {

        $em = $this->getDoctrine()->getManager()->getRepository(Topic::class); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $data = $em->findAll(); // Select * from evenements;
        $jsonContent =$normalizer->normalize($data, 'json' ,['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    #[Route('/api/deleteTopic/{id}', name: 'deleteTopic')]
    public function deleteTopic(Request $request,NormalizerInterface $normalizer,$id): Response
    {

        $topic = $this->getDoctrine()->getManager()->getRepository(Topic::class)->find($id); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
        $em = $this->getDoctrine()->getManager();

            $em->remove($topic);
            $em->flush();
            $jsonContent =$normalizer->normalize($topic, 'json' ,['groups'=>'post:read']);
            return new Response("information deleted successfully".json_encode($jsonContent));
    }

    #[Route('/api/addTopic', name: 'addTopic')]
    public function addTopic(NormalizerInterface $Normalizer,MailerInterface $maileer,Request $request,EntityManagerInterface $entityManager): Response
    {

        $topic = new Topic();

        $em = $this->getDoctrine()->getManager();
        $topic->setTitretopic($request->get('titretopic'));
        
        $topic->setDescription($request->get('description'));
        $topic->setDate(new \DateTime());
        $topic->setHide(0);
        $topic->setNbsujet(0);
        $topic->setAccepter(0);
        
        $user = $em->getRepository(User::class)->find($request->get('iduser'));

        $transport = Transport::fromDsn("smtp://pidev.beebly@gmail.com:bwukvzhiqbpwyrdc@smtp.gmail.com:587?encryption=tls");
        $mailer = new Mailer($transport);
       $emailTo = "emna.bouzouita@gmail.com";
        $email = (new Email())
   
        ->from('pidev.beebly@gmail.com')
        ->to($emailTo)
        ->subject('Topic added!')
        ->text('Sending emails is fun again!')
        ->html('
        
            <h1>A new topic has been added to the application</h1>
            <p>Dear'. $user->getNom() .',</p>
          
            <p>Thank you for your cooperation.</p>
            <p>Sincerely,<br>Beebly Team</p>
       ');   
    

$headers = $email->getHeaders();

$mailer->send($email);

        $topic->setIduser($user);
        
        $em->persist($topic);
        $em->flush();
            $jsonContent = $Normalizer->normalize($topic, 'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));

    }

    #[Route('/api/editTopicApi/{id}', name: 'editTopicApi')]
    public function editTopicApi($id,NormalizerInterface $Normalizer,Request $request,EntityManagerInterface $entityManager): Response
    {

     
        $em = $this->getDoctrine()->getManager();
        $topic = $em->getRepository(Topic::class)->find($id);

        $topic->setTitretopic($request->get('titretopic'));
        
        $topic->setDescription($request->get('description'));
        $topic->setDate(new \DateTime());
        $topic->setHide(0);
        $topic->setNbsujet(0);
        $topic->setAccepter(0);
        
        $user = $em->getRepository(User::class)->find($request->get('iduser'));

        $topic->setIduser($user);
        
        $em->persist($topic);
        $em->flush();
            $jsonContent = $Normalizer->normalize($topic, 'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));

    }
}

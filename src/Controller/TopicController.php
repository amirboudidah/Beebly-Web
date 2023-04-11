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
}

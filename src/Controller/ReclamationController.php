<?php

namespace App\Controller;
use App\Entity\User;

use App\Entity\Reponse;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
    #[Route('/front', name: 'reclamationFront', methods: ['GET'])]
    public function reclamationFront(EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $id =$session->get('id');
        if ($id!= null){
            $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findBy(['idUser' =>$id]);

        return $this->render('reclamation/userReclamation.html.twig', [
            'reclamations' => $reclamations,
        ]);
   
        }else{
            return $this->redirectToRoute('app_user_signin', [], Response::HTTP_SEE_OTHER);
        }
        }
       

    #[Route('/frontRec/{id}', name: 'showFrontt', methods: ['GET'])]
    public function showFrontt(EntityManagerInterface $entityManager,Reclamation $reclamation): Response
    {
        if ($reclamation->getIdReponse()!=null){
            $reponse =$entityManager
            ->getRepository(Reponse::class)
            ->find($reclamation->getIdReponse());
          
        return $this->render('reclamation/detailsRecFront.html.twig', [
            'reclamation' => $reclamation,
            'reponse' => $reponse
        ]);
        }else{
            return $this->render('reclamation/detailsRec.html.twig', [
                'reclamation' => $reclamation,
            ]);
        }
   
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $id = $session->get('id');
        $user = $entityManager
        ->getRepository(User::class)->find($id);
        
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setIdUser($user);
            $reclamation->setDate(new \DateTime());
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('reclamationFront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}

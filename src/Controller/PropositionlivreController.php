<?php

namespace App\Controller;

use App\Entity\Propositionlivre;
use App\Entity\User;
use App\Form\PropositionlivreType;
use Doctrine\ORM\EntityManagerInterface;
use  Doctrine\Persistence\ManagerRegistry;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/propositionlivre')]
class PropositionlivreController extends AbstractController
{
    #[Route('/', name: 'app_propositionlivre_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $propositionlivres = $entityManager
            ->getRepository(Propositionlivre::class)
            ->findAll();

        return $this->render('propositionlivre/index.html.twig', [
            'propositionlivres' => $propositionlivres,
        ]);
    }

    #[Route('/new', name: 'app_propositionlivre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $propositionlivre = new Propositionlivre();
        $form = $this->createForm(PropositionlivreType::class, $propositionlivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $c = $doctrine
                ->getRepository(User::class)
                ->find(1);
            $current_date = date('Y-m-d');
            $date_object = DateTime::createFromFormat('Y-m-d', $current_date);

            $propositionlivre->setIdclient($c);
            $propositionlivre->setDateproposition($date_object);
            $entityManager->persist($propositionlivre);
            $entityManager->flush();

            return $this->redirectToRoute('app_propositionlivre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('propositionlivre/new.html.twig', [
            'propositionlivre' => $propositionlivre,
            'form' => $form,
        ]);
    }

    #[Route('/{idpropositionlivre}', name: 'app_propositionlivre_show', methods: ['GET'])]
    public function show(Propositionlivre $propositionlivre): Response
    {
        return $this->render('propositionlivre/show.html.twig', [
            'propositionlivre' => $propositionlivre,
        ]);
    }

    #[Route('/{idpropositionlivre}/edit', name: 'app_propositionlivre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Propositionlivre $propositionlivre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PropositionlivreType::class, $propositionlivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_propositionlivre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('propositionlivre/edit.html.twig', [
            'propositionlivre' => $propositionlivre,
            'form' => $form,
        ]);
    }

    #[Route('/{idpropositionlivre}', name: 'app_propositionlivre_delete', methods: ['POST'])]
    public function delete(Request $request, Propositionlivre $propositionlivre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$propositionlivre->getIdpropositionlivre(), $request->request->get('_token'))) {
            $entityManager->remove($propositionlivre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_propositionlivre_index', [], Response::HTTP_SEE_OTHER);
    }
}

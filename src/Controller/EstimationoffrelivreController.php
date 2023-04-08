<?php

namespace App\Controller;

use App\Entity\Estimationoffrelivre;
use App\Form\EstimationoffrelivreType;
use App\Repository\EstimationoffrelivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/estimationoffrelivre')]
class EstimationoffrelivreController extends AbstractController
{
    #[Route('/', name: 'app_estimationoffrelivre_index', methods: ['GET'])]
    public function index(EstimationoffrelivreRepository $estimationoffrelivreRepository): Response
    {
        return $this->render('estimationoffrelivre/index.html.twig', [
            'estimationoffrelivres' => $estimationoffrelivreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_estimationoffrelivre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EstimationoffrelivreRepository $estimationoffrelivreRepository): Response
    {
        $estimationoffrelivre = new Estimationoffrelivre();
        $form = $this->createForm(EstimationoffrelivreType::class, $estimationoffrelivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $estimationoffrelivreRepository->save($estimationoffrelivre, true);

            return $this->redirectToRoute('app_estimationoffrelivre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('estimationoffrelivre/new.html.twig', [
            'estimationoffrelivre' => $estimationoffrelivre,
            'form' => $form,
        ]);
    }

    #[Route('/{idestimationoffrelivre}', name: 'app_estimationoffrelivre_show', methods: ['GET'])]
    public function show(Estimationoffrelivre $estimationoffrelivre): Response
    {
        return $this->render('estimationoffrelivre/show.html.twig', [
            'estimationoffrelivre' => $estimationoffrelivre,
        ]);
    }

    #[Route('/{idestimationoffrelivre}/edit', name: 'app_estimationoffrelivre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Estimationoffrelivre $estimationoffrelivre, EstimationoffrelivreRepository $estimationoffrelivreRepository): Response
    {
        $form = $this->createForm(EstimationoffrelivreType::class, $estimationoffrelivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $estimationoffrelivreRepository->save($estimationoffrelivre, true);

            return $this->redirectToRoute('app_estimationoffrelivre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('estimationoffrelivre/edit.html.twig', [
            'estimationoffrelivre' => $estimationoffrelivre,
            'form' => $form,
        ]);
    }

    #[Route('/{idestimationoffrelivre}', name: 'app_estimationoffrelivre_delete', methods: ['POST'])]
    public function delete(Request $request, Estimationoffrelivre $estimationoffrelivre, EstimationoffrelivreRepository $estimationoffrelivreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estimationoffrelivre->getIdestimationoffrelivre(), $request->request->get('_token'))) {
            $estimationoffrelivreRepository->remove($estimationoffrelivre, true);
        }

        return $this->redirectToRoute('app_estimationoffrelivre_index', [], Response::HTTP_SEE_OTHER);
    }
}

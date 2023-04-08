<?php

namespace App\Controller;

use App\Entity\Detailslivraison;
use App\Form\DetailslivraisonType;
use App\Repository\DetailslivraisonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/detailslivraison')]
class DetailslivraisonController extends AbstractController
{
    #[Route('/', name: 'app_detailslivraison_index', methods: ['GET'])]
    public function index(DetailslivraisonRepository $detailslivraisonRepository): Response
    {
        return $this->render('detailslivraison/index.html.twig', [
            'detailslivraisons' => $detailslivraisonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_detailslivraison_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DetailslivraisonRepository $detailslivraisonRepository): Response
    {
        $detailslivraison = new Detailslivraison();
        $form = $this->createForm(DetailslivraisonType::class, $detailslivraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detailslivraisonRepository->save($detailslivraison, true);

            return $this->redirectToRoute('app_detailslivraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('detailslivraison/new.html.twig', [
            'detailslivraison' => $detailslivraison,
            'form' => $form,
        ]);
    }

    #[Route('/{iddetailslivraison}', name: 'app_detailslivraison_show', methods: ['GET'])]
    public function show(Detailslivraison $detailslivraison): Response
    {
        return $this->render('detailslivraison/show.html.twig', [
            'detailslivraison' => $detailslivraison,
        ]);
    }

    #[Route('/{iddetailslivraison}/edit', name: 'app_detailslivraison_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Detailslivraison $detailslivraison, DetailslivraisonRepository $detailslivraisonRepository): Response
    {
        $form = $this->createForm(DetailslivraisonType::class, $detailslivraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $detailslivraisonRepository->save($detailslivraison, true);

            return $this->redirectToRoute('app_detailslivraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('detailslivraison/edit.html.twig', [
            'detailslivraison' => $detailslivraison,
            'form' => $form,
        ]);
    }

    #[Route('/{iddetailslivraison}', name: 'app_detailslivraison_delete', methods: ['POST'])]
    public function delete(Request $request, Detailslivraison $detailslivraison, DetailslivraisonRepository $detailslivraisonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$detailslivraison->getIddetailslivraison(), $request->request->get('_token'))) {
            $detailslivraisonRepository->remove($detailslivraison, true);
        }

        return $this->redirectToRoute('app_detailslivraison_index', [], Response::HTTP_SEE_OTHER);
    }
}

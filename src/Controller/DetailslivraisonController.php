<?php

namespace App\Controller;

use App\Entity\Detailslivraison;
use App\Entity\Estimationoffrelivre;
use App\Form\DetailslivraisonType;
use App\Repository\DetailslivraisonRepository;
use App\Repository\EstimationoffrelivreRepository;
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
    #[Route('/mesLivraisons', name: 'mesLivraisons', methods: ['GET'])]
    public function mesLivraison(DetailslivraisonRepository $detailslivraisonRepository): Response
    {
        return $this->render('detailslivraison/show_my_livraisons.html.twig', [
            'detailslivraisons' => $detailslivraisonRepository->findAll(),
        ]);
    }

    #[Route('/{idestimationoffrelivre}/new', name: 'app_detailslivraison_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DetailslivraisonRepository $detailslivraisonRepository,
                        Estimationoffrelivre $estimationoffrelivre,EstimationoffrelivreRepository $estimationoffrelivreRepository): Response
    {
        $detailslivraison = new Detailslivraison();
        $form = $this->createForm(DetailslivraisonType::class, $detailslivraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $estimationoffrelivre->setEtat("Acceptée");
            $estimationoffrelivreRepository->save($estimationoffrelivre,true);
            $detailslivraison->setEtatlivrasion("EnAttente");
            $detailslivraison->setIdestimationoffrelivre($estimationoffrelivre);
            $detailslivraisonRepository->save($detailslivraison, true);

            return $this->redirectToRoute('mesLivraisons', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('detailslivraison/new.html.twig', [
            'detailslivraison' => $detailslivraison,
            'form' => $form,'estimationoffrelivre' => $estimationoffrelivre
        ]);
    }

    #[Route('/{iddetailslivraison}', name: 'app_detailslivraison_show', methods: ['GET'])]
    public function show(Detailslivraison $detailslivraison): Response
    {
        return $this->render('detailslivraison/show.html.twig', [
            'detailslivraison' => $detailslivraison,
        ]);
    }

    #[Route('/{iddetailslivraison}/showMyLivraison', name: 'showMyLivraison', methods: ['GET'])]
    public function showMyLivraison(Detailslivraison $detailslivraison): Response
    {
        return $this->render('detailslivraison/show_details_of_my_livraison.html.twig', [
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

<?php

namespace App\Controller;

use App\Entity\ClotureAchat;
use App\Entity\Detailslivraison;
use App\Form\ClotureAchatType;
use App\Repository\ClotureAchatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cloture/achat')]
class ClotureAchatController extends AbstractController
{
    #[Route('/', name: 'app_cloture_achat_index', methods: ['GET'])]
    public function index(ClotureAchatRepository $clotureAchatRepository): Response
    {
        return $this->render('cloture_achat/index.html.twig', [
            'cloture_achats' => $clotureAchatRepository->findAll(),
        ]);
    }
    #[Route('/mesclotures', name: 'mesclotures', methods: ['GET'])]
    public function mesclotures(ClotureAchatRepository $clotureAchatRepository): Response
    {
        return $this->render('cloture_achat/show_my_clotures.html.twig', [
            'cloture_achats' => $clotureAchatRepository->findAll(),
        ]);
    }

    #[Route('/{iddetailslivraison}/new', name: 'app_cloture_achat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClotureAchatRepository $clotureAchatRepository,Detailslivraison $iddetailslivraison): Response
    {
        $clotureAchat = new ClotureAchat();
        $form = $this->createForm(ClotureAchatType::class, $clotureAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clotureAchat->setIdDetailsLivraison($iddetailslivraison);
            $clotureAchatRepository->save($clotureAchat, true);

            return $this->redirectToRoute('app_cloture_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cloture_achat/new.html.twig', [
            'cloture_achat' => $clotureAchat,
            'form' => $form,'iddetailslivraison'=>$iddetailslivraison
        ]);
    }

    #[Route('/{idCloture}', name: 'app_cloture_achat_show', methods: ['GET'])]
    public function show(ClotureAchat $clotureAchat): Response
    {
        return $this->render('cloture_achat/show.html.twig', [
            'cloture_achat' => $clotureAchat,
        ]);
    }
    #[Route('/{idCloture}/macloture', name: 'macloture', methods: ['GET'])]
    public function macloture(ClotureAchat $clotureAchat): Response
    {
        return $this->render('cloture_achat/show_details_of_my_cloture.html.twig', [
            'cloture_achat' => $clotureAchat,
        ]);
    }

    #[Route('/{idCloture}/edit', name: 'app_cloture_achat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ClotureAchat $clotureAchat, ClotureAchatRepository $clotureAchatRepository): Response
    {
        $form = $this->createForm(ClotureAchatType::class, $clotureAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clotureAchatRepository->save($clotureAchat, true);

            return $this->redirectToRoute('app_cloture_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cloture_achat/edit.html.twig', [
            'cloture_achat' => $clotureAchat,
            'form' => $form,
        ]);
    }

    #[Route('/{idCloture}', name: 'app_cloture_achat_delete', methods: ['POST'])]
    public function delete(Request $request, ClotureAchat $clotureAchat, ClotureAchatRepository $clotureAchatRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clotureAchat->getIdCloture(), $request->request->get('_token'))) {
            $clotureAchatRepository->remove($clotureAchat, true);
        }

        return $this->redirectToRoute('app_cloture_achat_index', [], Response::HTTP_SEE_OTHER);
    }
}

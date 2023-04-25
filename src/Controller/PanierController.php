<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

class PanierController extends AbstractController
{

    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, LivreRepository $livreRepository): Response
    {
        $panier = $session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $id => $quantity){
            $panierWithData[]= [
                'livre' => $livreRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $total =0;
        foreach ($panierWithData as $item){
            $totalItem = $item['livre']->getPrix() * $item['quantity'];
            $total += $totalItem;
        }
        return $this->render('panier/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

     #[Route('/panier/add/{id}', name: 'panier_add')]
    public function add($id, SessionInterface $session){

        $panier =$session->get('panier',[]);
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;;
        }
        $session->set('panier',$panier);
       return $this->redirectToRoute("app_panier");

     }

    #[Route('/panier/remove/{id}', name: 'panier_remove')]
    public function remove($id, SessionInterface $session){
        $panier = $session->get('panier',[]);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier',$panier);
        return $this->redirectToRoute("app_panier");
     }

    /**
     * @Route("/panier/pdf", name="panier_pdf", methods={"POST"})
     */
    public function generatePdf(EntityManagerInterface $entityManager): Response
    {
        $items = $entityManager->getRepository(Item::class)->findAll();
        $total = 0;

        foreach ($items as $item) {
            $total += $item->getLivre()->getPrix() * $item->getQuantity();
        }

        // générer le contenu du PDF
        $pdfContent = $this->renderView('panier/index.html.twig', [
            'items' => $items,
            'total' => $total,
        ]);

        // créer une réponse PDF
        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');

        // retourner la réponse
        return $response;
    }


}

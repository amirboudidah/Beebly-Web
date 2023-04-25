<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\Livre1Type;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/catalogue')]
class CatalogueController extends AbstractController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_catalogue_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('catalogue/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_catalogue_show', methods: ['GET'])]
    public function show(Livre $livre): Response
    {
        return $this->render('catalogue/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    /**
     * @Route("/favoris/ajouter/{id}", name="app_favoris_ajouter")
     */
    public function ajouter(Request $request, Livre $livre)
    {
        // Récupérer l'utilisateur courant
        $user = $this->getUser();

        // Vérifier si l'utilisateur a déjà ajouté ce livre aux favoris
        if ($user->getFavoris()->contains($livre)) {
            // Retourner une réponse JSON pour indiquer que le livre est déjà dans les favoris de l'utilisateur
            $response = new Response();
            $response->setContent(json_encode(array(
                'message' => 'Le livre est déjà dans vos favoris.'
            )));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        // Ajouter le livre aux favoris de l'utilisateur
        $user->addFavori($livre);

        // Enregistrer les modifications dans la base de données
        $entityManager = $this->entityManager;
        $entityManager->persist($user);
        $entityManager->flush();

        // Retourner une réponse JSON pour confirmer que le livre a été ajouté aux favoris
        $response = new Response();
        $response->setContent(json_encode(array(
            'message' => 'Le livre a été ajouté à vos favoris avec succès.'
        )));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }









}

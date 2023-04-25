<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\QrCodeService;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/evenement')]
class EvenementController extends AbstractController
{

    #[Route('/eventFilter/{emplacement}', name: 'eventFilter', methods: ['GET','POST'])]
    public function eventFilter($emplacement,Request $request, EntityManagerInterface $entityManager)
    {
        $events = $entityManager->createQuery('
            SELECT e
            FROM App\Entity\Evenement e
            WHERE e.emplacement = :emplacement
        ')
            ->setParameter('emplacement', $emplacement)
            ->getResult();
        
        return $this->render('evenement/listEventsFront.html.twig', [
            'evenements' => $events,
        ]);
        
    }

 
    #[Route('/eventSearch', name: 'eventSearch', methods: ['GET','POST'])]
    public function search(Request $request, EntityManagerInterface $entityManager)
    {
        // Get search parameters from request
        $searchType = $request->query->get('searchType');
        $searchValue = $request->query->get('searchValue');
     
        // Query the database with search parameters using DQL
        $query = $entityManager->createQuery("SELECT t FROM App\Entity\Evenement t WHERE t.$searchType LIKE :searchValue")
            ->setParameter('searchValue', '%' . $searchValue . '%');
        $evenements = $query->getResult();
       
         // Manually serialize entities to avoid circular references
         $serializedEvenements = [];
         foreach ($evenements as $evenement) {
             $serializedEvenements[] = [
                
                'id' => $evenement->getId(),
                 'libelle' => $evenement->getLibelle(),
                 'date' => $evenement->getDate(),
                 'description' => $evenement->getDescription(),
                 'emplacement' => $evenement->getEmplacement(),
                 'nbPlace' => $evenement->getNbPlace(),
                 'duree' => $evenement->getDuree()
             ];
         }
            // Create JSON response
        $response = new JsonResponse();
        $response->setData(['evenements' => $serializedEvenements]);
        return $response;
    }
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/eventsFront', name: 'eventsFront', methods: ['GET'])]
    public function eventsFront(EntityManagerInterface $entityManager): Response
    {
        
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();

        return $this->render('evenement/listEventsFront.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenement->setImage("");
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }
    #[Route('/showEventFront/{id}', name: 'showEventFront', methods: ['GET'])]
    public function showEventFront(Evenement $evenement): Response
    {
        $writer = new PngWriter();
        $qrCode = QrCode::create('Libelle  : '. $evenement->getLibelle(). ' Description : ' .$evenement->getDescription() . ' Emplacement : ' . $evenement->getEmplacement()  . 'Date :' . $evenement->getDate() )
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(120)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        $logo = null;
        $label = Label::create('')->setFont(new NotoSans(8));
 
        $qrCodes = [];
        $qrCodes['img'] = $writer->write($qrCode, $logo)->getDataUri();
        $qrCodes['simple'] = $writer->write(
                                $qrCode,
                                null,
                                $label->setText('Event')
                            )->getDataUri();
 
        $qrCode->setForegroundColor(new Color(255, 0, 0));
        $qrCode->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 0, 0));
 
        $qrCode->setSize(200)->setForegroundColor(new Color(0, 0, 0))->setBackgroundColor(new Color(255, 255, 255));
        $qrCodes['withImage'] = $writer->write(
            $qrCode,
            null,
            $label->setText('With Image')->setFont(new NotoSans(10))
        )->getDataUri();
        return $this->render('evenement/showEventFront.html.twig', [
            'evenement' => $evenement,
            'qrCodes'=>$qrCodes,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}

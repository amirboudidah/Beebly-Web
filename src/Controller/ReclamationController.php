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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Twilio\Rest\Client;
#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/dashboardRecl', name: 'app_reclamation_dashboard', methods: ['GET'])]
    public function dashboardRec(EntityManagerInterface $entityManager): Response
    {
        // Create a DQL query
$dqlStatusLivre = "
    SELECT COUNT(r) as recCount
    FROM App\Entity\Reclamation r
    WHERE r.type = :type
";
// Execute the DQL query
$query = $entityManager->createQuery($dqlStatusLivre)
->setParameter('type', 'Livre');

// Get the results
$results = $query->getResult();
$recCountLivre=$results[0]['recCount'];

       // Create a DQL query
       $dqlStatusAutre = "
       SELECT COUNT(r) as recAutreCount
       FROM App\Entity\Reclamation r
       WHERE r.type = :type
   ";
   // Execute the DQL query
   $query = $entityManager->createQuery($dqlStatusAutre)
   ->setParameter('type', 'Autre');
   
   // Get the results
   $results = $query->getResult();
   $recAutreCount=$results[0]['recAutreCount'];
   

   
       // Create a DQL query
       $dqlStatusLivraison = "
       SELECT COUNT(r) as recAutreCount
       FROM App\Entity\Reclamation r
       WHERE r.type = :type
   ";
   // Execute the DQL query
   $query = $entityManager->createQuery($dqlStatusLivraison)
   ->setParameter('type', 'Livraison');
   
   // Get the results
   $results = $query->getResult();
   $recCountLivraison=$results[0]['recAutreCount'];
   $chart_data=[$recAutreCount,$recCountLivraison,$recCountLivre];
        return $this->render('reclamation/reclamationDashboard.html.twig', [
            'recCountLivre' => $recCountLivre,
            'recAutreCount' => $recAutreCount,
            'recCountLivraison' => $recCountLivraison,
            'chart_data' => json_encode($chart_data),

        ]);
    }
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
            $reclamation->setStatus('Non traité');
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

    #[Route('/api/reclamationApi', name: 'reclamationApi')]
    public function reclamationApi(Request $request,NormalizerInterface $normalizer): Response
    {

        $em = $this->getDoctrine()->getManager()->getRepository(Reclamation::class); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES

        $data = $em->findAll(); // Select * from reclamations;
        $jsonContent =$normalizer->normalize($data, 'json' ,['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }
    #[Route('/api/deleteReclamation/{id}', name: 'deleteReclamation')]
    public function deleteReclamation(Request $request,NormalizerInterface $normalizer,$id): Response
    {

        $reclamation = $this->getDoctrine()->getManager()->getRepository(Reclamation::class)->find($id); // ENTITY MANAGER ELY FIH FONCTIONS PREDIFINES
        $em = $this->getDoctrine()->getManager();

            $em->remove($reclamation);
            $em->flush();
            $jsonContent =$normalizer->normalize($reclamation, 'json' ,['groups'=>'post:read']);
            return new Response("information deleted successfully".json_encode($jsonContent));
    }
    #[Route('/api/addReclamation', name: 'addReclamation')]
    public function addReclamation(NormalizerInterface $Normalizer,Request $request,EntityManagerInterface $entityManager): Response
    {

        $reclamation = new Reclamation();

        $em = $this->getDoctrine()->getManager();
        $reclamation->setType($request->get('type'));
        $reclamation->setSujet($request->get('sujet'));
        $reclamation->setDescription($request->get('description'));
        $reclamation->setDate(new \DateTime());
        $reclamation->setPhoto("");
        $reclamation->setStatus($request->get('status'));
        $user = $em->getRepository(User::class)->find($request->get('idUser'));

        $reclamation->setIdUser($user);
        // Replace with your Twilio account SID, auth token, and Twilio phone number
        $twilioAccountSid = 'ACd476e6133d3fa563ae799674f3a7008b';
        $twilioAuthToken = 'bb9200db08f397ce844cc0610b3a02e9';
        $twilioPhoneNumber = '+16813396798';

        // Create a Twilio client instance
        $twilioClient = new Client($twilioAccountSid, $twilioAuthToken);


        $phoneNumber="+21650730142";
        // Send SMS using Twilio
        $message = $twilioClient->messages->create(
            $phoneNumber, // recipient's phone number
            [
                'from' => $twilioPhoneNumber, // your Twilio phone number
                'body' => 'a new reclamation added please check it'// SMS body
            ]
        );
        $em->persist($reclamation);
        $em->flush();
            $jsonContent = $Normalizer->normalize($reclamation, 'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));

    }

    #[Route('/api/editReclamationApi/{id}', name: 'editReclamationApi')]
    public function editReclamationApi($id,NormalizerInterface $Normalizer,Request $request,EntityManagerInterface $entityManager): Response
    {
        $em = $this->getDoctrine()->getManager();

        $reclamation = $em->getRepository(Reclamation::class)->find($id);

       
        $reclamation->setType($request->get('type'));
        $reclamation->setSujet($request->get('sujet'));
        $reclamation->setDescription($request->get('description'));
        
        $reclamation->setPhoto("");
        $reclamation->setStatus($request->get('status'));
        $user = $em->getRepository(User::class)->find($request->get('idUser'));

        $reclamation->setIdUser($user);
        
        $em->persist($reclamation);
        $em->flush();
        
            $jsonContent = $Normalizer->normalize($reclamation, 'json',['groups'=>'post:read']);
            return new Response(json_encode($jsonContent));

    }

}

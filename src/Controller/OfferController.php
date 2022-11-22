<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferAddType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    #[Route('/offres', name: 'app_offres_home')]
    public function index(): Response
    {
        return $this->render('offres/index.html.twig', [
            'controller_name' => 'OffresController',
        ]);
    }

    #[Route('/deposer-offre', name: 'app_offres_new')]
    public function add(Request $request, ManagerRegistry $doctrine)
    {
        $offer = new Offer();

        $form = $this->createForm(OfferAddType::class, $offer);
        $form->handleRequest($request);
        
        $entityManager = $doctrine->getManager();
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $offer = new Offer();
            $offer = $form->getData();
            $$entityManager->persist($offer);
            $$entityManager->flush();
            return $this->redirectToRoute("app_default");
        }
        return $this->render('offres/deposer-offre.html.twig', 
        [
            'OfferControllerNew' => $form->createView(),
        ]);

    }

}

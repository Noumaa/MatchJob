<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferAddType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    #[Route('/offres', name: 'app_offres_show')]
    public function index(ManagerRegistry $doctrine): Response
    {
        /*
            Cette fonction retourne l'ensemble des offres
            Etat : fonctionnelle
        */
        $lesOffres = $doctrine->getRepository(Offer::class)->findAll();

        return $this->render('offres/voir-les-offres.html.twig', 
        [
            'lesOffres' => $lesOffres,
            'controller_name' => 'Les Offres',
        ]);
    }

    #[IsGranted("ROLE_USER")]
    #[Route('/deposer-offre', name: 'app_offres_new')]
    public function add(Request $request, ManagerRegistry $doctrine)
    {
        /*
            Cette fonction a pour but d'enregistrer dans la base de données
            une offre déposer par une entreprise

            Etat : non fonctionnelle
        */
       
        $entityManager = $doctrine->getManager();
        $offer = new Offer();

        // $form = $this->createFormBuilder($offer)
        //              ->add('label')
        //              ->add('description')
        //              ->add('salary')
        //              ->getForm();

        $form = $this->createForm(OfferAddType::class,$offer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $offer->setUser($this->getUser());
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute("app_default");
        }
        return $this->render('offres/deposer-offre.html.twig', 
        [
            'OfferControllerNew' => $form->createView(),
        ]);

    }

}

<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferAddType;
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
    public function add(Request $request)
    {
        $offer = new Offer();
        $form = $this->createForm(OfferAddType::class);
        $form->handleRequest($request);

        $manager->persist($offer);
        $manager->flush();
        dump($offer);
                

        
        return $this->render('offres/deposer-offre.html.twig', 
        [
            'OfferControllerNew' => $form->createView(),
        ]);

    }

}

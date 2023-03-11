<?php

namespace App\Controller\Business;

use App\Entity\Demands;
use App\Entity\Offer;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/pro/dashboard', name: 'app_business_dashboard')]
    #[IsGranted('ROLE_BUSINESS')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $offers = $doctrine->getManager()->getRepository(Offer::class)->findAll();
        //Nombre de demandes au total (tout les offres) pour une entreprise
        $nbr_demands = 0;
        foreach($this->getUser()->getOffers() as $offer)
        {
            $nbr_demands+= count($offer->getDemands());
        }

        //Nombre de vues
        $nbr_views = 0;
        foreach($this->getUser()->getOffers() as $offer)
        {
            $nbr_views+= $offer->getViews();
        }

        return $this->render('business/dashboard/index.html.twig', [
            'nbr_demands' => $nbr_demands,
            'nbr_views' => $nbr_views, 
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/pro/profil', name: 'app_business_profile')]
    #[IsGranted('ROLE_BUSINESS')]
    public function profile(ManagerRegistry $doctrine): Response
    {
        return $this->render('business/dashboard/editer.html.twig');
    }
}

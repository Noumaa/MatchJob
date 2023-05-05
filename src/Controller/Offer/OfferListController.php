<?php

namespace App\Controller\Offer;

use App\Entity\Offer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferListController extends AbstractController
{
    #[Route('/offres', name: 'app_offer_list')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $offers = $doctrine->getRepository(Offer::class)->findBy(['isArchived' => 0]);

        return $this->render('offer/list.html.twig', [
            'offers' => $offers,
        ]);
    }
}

<?php

namespace App\Controller\Offer;

use App\Entity\Offer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    #[Route('/offres', name: 'app_offer_list')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $offers = $doctrine->getRepository(Offer::class)->findBy(['isArchived' => 0]);

        return $this->render('offer/list.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/offres/{id}', name: 'app_offer_detail')]
    public function detail(Request $request, ManagerRegistry $doctrine, Offer $offer): Response
    {
        // TODO handle not found
        $offer = $doctrine->getRepository(Offer::class)->findOneBy(['id' => $offer->getId(), 'isArchived' => 0]);

        $duration = $offer->getDuration();

        $years = $duration->format('%Y années');
        $month = $duration->format('%M mois');
        $days = $duration->format('%D jours');

        $dateInterval = $days . " " . $month . " " . $years . " ";

        return $this->render('offer/detail.html.twig', [
            'offer' => $offer,
            'duration' => $dateInterval,
        ]);

//        if ($request->isMethod('POST'))
//        {
//            if ($this->isGranted("ROLE_USER"))
//            {
//                $success = $applications->create($offer, $this->getUser());
//
//                if ($success)
//                {
//                    $message = "<strong>Confirmé !</strong> La candidature a bien été déposé.";
//                    $messageType = 'success';
//                }
//                else
//                {
//                    $message = "<strong>Erreur !</strong> Vous avez déjà déposé votre candidature !";
//                    $messageType = 'danger';
//                }
//            }
//        }
    }
}

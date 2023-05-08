<?php

namespace App\Controller\Offer;

use App\Entity\Offer;
use App\Form\OfferFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

        if ($duration != null)
        {
            $years = $duration->format('%Y années');
            $month = $duration->format('%M mois');
            $days = $duration->format('%D jours');

            $dateInterval = $days . " " . $month . " " . $years . " ";
        }
        else
        {
            $dateInterval = "Non renseigné";
        }

        return $this->render('offer/detail.html.twig', [
            'offer' => $offer,
            'duration' => $dateInterval,
        ]);
    }

    #[Route('pro/deposer-une-offre', name: 'app_offer_create')]
    #[IsGranted("ROLE_BUSINESS")]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $offer = new Offer();

        $form = $this->createForm(OfferFormType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $offer->setUser($this->getUser());

            $entityManager->persist($offer);
            $entityManager->flush();

            $this->addFlash('success', 'Félicitations, votre offre est officiellement en ligne !');

            return $this->redirectToRoute("app_offer_detail", [
                "id" => $offer->getId()
            ]);
        }

        return $this->render('dashboard/business/create_offer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

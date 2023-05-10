<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Offer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ApplicationController extends AbstractController
{
    #[Route('/offres/{id}/postuler', name: 'app_apply', methods: 'POST')]
    #[IsGranted('ROLE_PERSON')]
    public function apply(Offer $offer, ManagerRegistry $doctrine): Response
    {
        if ($offer->isIsArchived())
        {
            $this->addFlash("error", "L'offre n'existe plus.");
            return $this->redirectToRoute('app_offer_list');
        }

        $applied = $this->getUser()->getApplications()->exists(function ($key, $e) use ($offer) {
            return $e->getOffer() == $offer;
        });

        if ($applied)
        {
            $this->addFlash("error", "Vous avez déjà postulé pour cette offre.");
        }
        else
        {
            $application = new Application();
            $application->setUser($this->getUser());
            $application->setOffer($offer);

            $manager = $doctrine->getManager();

            $manager->persist($application);
            $manager->flush();

            $this->addFlash("success", "Félicitations, vous venez de postuler pour le poste de " . $offer->getLabel() . ".");
        }

        return $this->redirectToRoute('app_offer_detail', [
            'id' => $offer->getId(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Demands;
use App\Entity\Offer;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DemandController extends AbstractController
{
    #[IsGranted("ROLE_PERSON")]
    #[Route('/offres/{id}/postuler', name: 'app_apply')]
    public function apply(Request $request, ManagerRegistry $doctrine, Offer $offer): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();

        $demands = [];
        foreach ($offer->getDemands() as $d) {
            if ($d->getIndividual() === $user) $demands[] = $d;
        }

        if (!empty($demands)) {
            $messageFalse = "Vous avez déjà déposé votre candidature !";
            return $this->render('offer/detail.html.twig',
                [
                    'oneOffer' => $offer,
                    'messageFalse' => $messageFalse,
                ]);
        }

        $demand = new Demands();

        $demand->setOffer($offer);
        $demand->setIndividual($user);

        $entityManager->persist($demand);
        $entityManager->flush();

        $messageTrue = "La candidature a bien été déposé.";
        return $this->render('offer/detail.html.twig',
            [
                'messageTrue' => $messageTrue,
                'oneOffer' => $offer,
            ]);
    }
}

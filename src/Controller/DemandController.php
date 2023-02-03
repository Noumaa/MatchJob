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
    #[IsGranted("ROLE_USER")]
    #[Route('/deposer-candidature/{id}', name: 'app_newDemand')]
    public function apply(Request $request, ManagerRegistry $doctrine, Offer $oneOffer): Response
    { 
        $entityManager = $doctrine->getManager();
        if(in_array("ROLE_BUSINESS", $this->getUser()->getRoles()))
        {
            return $this->redirectToRoute("app_listOffer");
        }
        $user = $this->getUser();
        $NbDemandsOneOffer = $doctrine->getRepository(Demands::class)->findBy(["Individual" => $user->getId(), "Offer" => $oneOffer->getId()]);
        if(empty($NbDemandsOneOffer))
        {
            $demand = new Demands();
            $demand->setOffer($oneOffer);
            $demand->setIndividual($user);
            $demand->setDateAdd(new DateTimeImmutable());
            $demand->setDateUpdate(new DateTimeImmutable());
            $entityManager->persist($demand);
            $entityManager->flush();
            $messageTrue = "La candidature a bien été déposé.";
            return $this->render('offer/detail.html.twig', 
            [
                'messageTrue' => $messageTrue,
                'oneOffer' => $oneOffer,
            ]);
        }
        else
        {
            $messageFalse = "Vous avez déjà déposé votre candidature !";
            return $this->render('offer/detail.html.twig', 
            [
                'oneOffer' => $oneOffer,
                'messageFalse' => $messageFalse,
            ]);
        }
    }
}

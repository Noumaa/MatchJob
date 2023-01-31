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
        $user = $this->getUser();
        $oneOffer = $doctrine->getRepository(Offer::class)->findOneBy(['id' => $oneOffer->getId()]);
        if(in_array("ROLE_BUSINESS", $this->getUser()->getRoles()))
        {
            return $this->redirectToRoute("app_listOffer");
        }
        $demand = new Demands();
        $demand->setIdOffer($oneOffer);
        $demand->setIdIndividual($user);
        $demand->setDateAdd(new DateTimeImmutable());
        $demand->setDateUpdate(new DateTimeImmutable());
        $entityManager->persist($demand);
        $entityManager->flush();
        return $this->redirectToRoute("app_profile");
        // return $this->render('demand/index.html.twig', 
        // [
        //     'controller_name' => 'DemandController',
        // ]);
    }
}

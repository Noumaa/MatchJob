<?php
namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Service\Applications\Applications;
use DateTimeImmutable;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class OfferController extends AbstractController
{
    #[Route('/offres', name: 'app_offer_list')]
    public function list(ManagerRegistry $doctrine) : Response
    {
        $Offers = $doctrine->getRepository(Offer::class)->findBy(['isArchived' => 0]);

        return $this->render('offer/list.html.twig', 
        [
            'Offers' => $Offers,
        ]);
    }


    #[IsGranted("ROLE_BUSINESS")]
    #[Route('pro/offres/deposer', name: 'app_offer_create')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {       
        $entityManager = $doctrine->getManager();
        $offer = new Offer();

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $offer->setUser($this->getUser());
            $offer->setDuration(date_diff($offer->getstartAt(),$offer->getEndAt()));
            
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute("app_offer_detail", [ "id" => $offer->getId() ]);
        }

        return $this->render('offer/create.html.twig', 
        [
            'Offer' => $form->createView(),
        ]);
    }


    #[Route('/offres/{id}', name: 'app_offer_detail')]
    public function detail(Request $request, ManagerRegistry $doctrine, Applications $applications, Offer $oneOffer): Response
    {
        $oneOffer = $doctrine->getRepository(Offer::class)->findOneBy(['id' => $oneOffer->getId()]);
        $duration = $oneOffer->getDuration();
        $years = $duration->format('%Y années');
        $month = $duration->format('%M mois');
        $days = $duration->format('%D jours');
        $dateInterval = $days . " " . $month . " " . $years . " ";

        if ($request->isMethod('POST'))
        {
            $success = $applications->create($oneOffer, $this->getUser());

            if ($success)
            {
                $message = "<strong>Confirmé !</strong> La candidature a bien été déposé.";
                $messageType = 'success';
            }
            else
            {
                $message = "<strong>Erreur !</strong> Vous avez déjà déposé votre candidature !";
                $messageType = 'danger';
            }
        }

        return $this->render('offer/detail.html.twig',
            [
                'oneOffer' => $oneOffer,
                'duration' => $dateInterval,
                'message' => $message ?? null,
                'messageType' => $messageType ?? null,
            ]);
    }


    #[IsGranted("ROLE_BUSINESS")]
    #[Route('pro/offres/{id}/modifier', name: 'app_offer_edit')]
    public function edit(Request $request, ManagerRegistry $doctrine, Offer $oneOffer): Response
    {
        if($this->getUser() == $oneOffer->getUser())
        {
            if(!$oneOffer->IsArchived())
            {
                $entityManager = $doctrine->getManager();
                $form = $this->createForm(OfferType::class, $oneOffer);
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid())
                {
                    $oneOffer->setUser($this->getUser());
                    $oneOffer->setDuration(date_diff($oneOffer->getstartAt(),$oneOffer->getEndAt()));
                    $oneOffer->setCreatedAt(new DateTimeImmutable());
                    $oneOffer = $form->getData();
                    $entityManager->flush();
                    return $this->redirectToRoute("app_offer_detail",
                    [
                        'id' => $oneOffer->getId()
                    ]);
                }
                return $this->render('offer/create.html.twig', 
                [
                    'Offer' => $form->createView(),
                ]);
            }
        }
        return $this->redirectToRoute("app_offer_list");
    }

    
    #[IsGranted("ROLE_BUSINESS")]
    #[Route('/pro/offres/{id}/supprimer', name: 'app_offer_delete')]
    public function delete(ManagerRegistry $doctrine, Offer $oneOffer): Response
    {
        if($oneOffer->getUser() == $this->getUser())
        {
            $manager = $doctrine->getManager();
            $oneOffer->setIsArchived(true);
            $manager->persist($oneOffer);
            $manager->flush();
            return $this->redirectToRoute('app_offer_list');
        }
        return $this->redirectToRoute('app_offer_list');
    }
}

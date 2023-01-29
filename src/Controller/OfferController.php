<?php
namespace App\Controller;

use App\Entity\Offer;
use App\Entity\User;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    #[Route('/offres', name: 'app_listOffer')]
    public function list(ManagerRegistry $doctrine) : Response
    {
        /*
            Cette fonction retourne l'ensemble des offres
            Etat : fonctionnelle
        */
        $Offers = $doctrine->getRepository(Offer::class)->findBy(['isArchived' => 0]);
        // if($this->getUser() == null)
        // {
        //     return $this->render('offer/list.html.twig', 
        //     [
        //         'isBusiness' => false,
        //         'Offers' => $Offers,
        //         'controller_name' => 'Les Offres',
        //     ]);
        // }
        if($this->getUser() != null && in_array("BUSINESS", $this->getUser()->getRoles()))
        {
            return $this->render('offer/list.html.twig', 
            [
                'isBusiness' => true,
                'Offers' => $Offers,
                'controller_name' => 'Les Offres',
            ]);
        }
        //dd($Offers);
        return $this->render('offer/list.html.twig', 
        [
            'isBusiness' => false,
            'Offers' => $Offers,
            'controller_name' => 'Les Offres',
        ]);
    }

    

    #[IsGranted("ROLE_USER")]
    #[Route('offres/deposer-offre', name: 'app_createOffer')]
    public function add(Request $request, ManagerRegistry $doctrine)
    {
        /*
            Cette fonction a pour but d'enregistrer dans la base de données
            une offre déposé par une entreprise

            Etat : non fonctionnelle
        */

        if($this->getUser() != null && count($this->getUser()->getRoles()) == 2 && in_array("BUSINESS",$this->getUser()->getRoles()))
        {
            $entityManager = $doctrine->getManager();
            $offer = new Offer();

            $form = $this->createForm(OfferType::class, $offer);
            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid())
            {
                $offer->setUser($this->getUser());
                $offer->setDuration(date_diff($offer->getstartAt(),$offer->getEndAt()));
                $offer->setCreatedAt(new DateTimeImmutable());
                $offer = $form->getData();
                
                $entityManager->persist($offer);
                $entityManager->flush();
                return $this->redirectToRoute("app_listOffer");
            }
            return $this->render('offer/create.html.twig', 
            [
                'Offer' => $form->createView(),
            ]);
        }
        return $this->redirectToRoute("app_listOffer");
    }

    #[Route('/offres/edit/{id}', name: 'app_editOffer')]
    public function edit(Request $request, ManagerRegistry $doctrine, Offer $oneOffer): Response
    {
        /*
            Cette fonction a pour but de modifier une offre

            Etat : non fonctionnelle
        */
        $entityManager = $doctrine->getManager();
        $form = $this->createForm(OfferType::class, $oneOffer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            if($this->getUser() != null)
            {
                
                $oneOffer->setUser($this->getUser());
                $oneOffer->setDuration(date_diff($oneOffer->getstartAt(),$oneOffer->getEndAt()));
                $oneOffer->setCreatedAt(new DateTimeImmutable());
                $oneOffer = $form->getData();
                $entityManager->flush();
                return $this->redirectToRoute("app_detailOffer",
                [
                    'id' => $oneOffer->getId()
                ]);
            }
            else
            {
                return $this->redirectToRoute("app_listOffer");
            }
            
        }
        return $this->render('offer/create.html.twig', 
        [
            'Offer' => $form->createView(),
        ]);
        
        
    }

    #[Route('/offres/delete/{id}', name: 'app_deleteOffer')]
    public function delete(ManagerRegistry $doctrine, Offer $oneOffer): Response
    {
        /*
            Cette fonction a pour but de supprimer une offre de la base de donnée

            Etat : fonctionnelle
        */
        if($this->getUser() != null)
        {
            $manager = $doctrine->getManager();
            $manager->remove($oneOffer);
            $manager->flush();
            return $this->redirectToRoute('app_listOffer');
        }
        else
        {
            return $this->redirectToRoute('app_listOffer');
        }
        
    }

    #[Route('/offres/show/{id}', name: 'app_detailOffer')]
    public function detail(ManagerRegistry $doctrine, Offer $oneOffer): Response
    {
        $oneOffer = $doctrine->getRepository(Offer::class)->findOneBy(['id' => $oneOffer->getId()]);
        $form = $this->createForm(OfferType::class,$oneOffer);
        $duration = $oneOffer->getDuration();
        $years = $duration->format('%Y années');
        $month = $duration->format('%M mois');
        $days = $duration->format('%D jours');
        $dateInterval = $days . " " . $month . " " . $years . " ";

        $proOffer = $doctrine->getRepository(User::class)->findOneBy(['id' => $oneOffer->getUser()]);
        return $this->render('offer/detail.html.twig', 
        [
            'oneOffer' => $oneOffer,
            'proOffer' => $proOffer,
            'duration' => $dateInterval,
            'controller_name' => $oneOffer->getLabel(),
        ]);
    }

    

}

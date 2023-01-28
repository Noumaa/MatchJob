<?php
namespace App\Controller;

use App\Entity\Offer;
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
    #[Route('/offres', name: 'app_offres_show_all')]
    public function index(ManagerRegistry $doctrine) : Response
    {
        /*
            Cette fonction retourne l'ensemble des offres
            Etat : fonctionnelle
        */
        $lesOffres = $doctrine->getRepository(Offer::class)->findAll();

        return $this->render('offres/voir-les-offres.html.twig', 
        [
            'lesOffres' => $lesOffres,
            'controller_name' => 'Les Offres',
        ]);
    }

    

    #[IsGranted("ROLE_USER")]
    #[Route('offres/deposer-offre', name: 'app_offres_new')]
    public function add(Request $request, ManagerRegistry $doctrine)
    {
        /*
            Cette fonction a pour but d'enregistrer dans la base de données
            une offre déposé par une entreprise

            Etat : non fonctionnelle
        */
       
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
            return $this->redirectToRoute("app_offres_show_all");
        }
        return $this->render('offres/deposer-offre.html.twig', 
        [
            'Offer' => $form->createView(),
        ]);

    }

    #[Route('/offres/show/{id}', name: 'app_offres_show_one')]
    public function showOne(ManagerRegistry $doctrine, Offer $uneOffre): Response
    {
        $uneOffre = $doctrine->getRepository(Offer::class)->findOneBy(['id' => $uneOffre->getId()]);
        $form = $this->createForm(OfferType::class,$uneOffre);
        $duration = $uneOffre->getDuration();
        $years = $duration->format('%Y années');
        $month = $duration->format('%M mois');
        $days = $duration->format('%D jours');

        $dateInterval = $days . " " . $month . " " . $years . " ";

        return $this->render('offres/voir-une-offre.html.twig', 
        [
            'uneOffre' => $form->createView(),
            'duration' => $dateInterval,
            'controller_name' => $uneOffre->getLabel(),
        ]);
    }

    #[Route('/offres/delete/{id}', name: 'app_offres_delete_one')]
    public function deleteOne(ManagerRegistry $doctrine, Offer $oneOffer): Response
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
            return $this->redirectToRoute('app_offres_show_all');
        }
        else
        {
            return $this->redirectToRoute('app_offres_show_all');
        }
        
    }

    #[Route('/offres/edit/{id}', name: 'app_offres_edit')]
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
                return $this->redirectToRoute("app_offres_show_one",
                [
                    'id' => $oneOffer->getId()
                ]);
            }
            else
            {
                return $this->redirectToRoute("app_offres_show_all");
            }
            
        }
        return $this->render('offres/deposer-offre.html.twig', 
        [
            'Offer' => $form->createView(),
        ]);
        
        
    }

}

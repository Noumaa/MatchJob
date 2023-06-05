<?php
namespace App\Controller\Business;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Entity\Category;
use App\Service\Applications\Applications;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Stmt\Catch_;
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
        $categories = $doctrine->getRepository(Category::class)->findAll();

        return $this->render('offer/list.html.twig', 
        [
            'Offers' => $Offers,
            "Categories" => $categories
        ]);
    }

    #[Route('/offres/filter/{id}', name: 'app_offer_filter')]
    public function filterOffer(ManagerRegistry $doctrine, Category $category) : Response
    {
        $Offers = $doctrine->getRepository(Offer::class)->findByCategory($category);
        $categories = $doctrine->getRepository(Category::class)->findAll();
        return $this->render('offer/list.html.twig', 
        [
            'Offers' => $Offers,
            "Categories" => $categories
        ]);
    }

    #[Route('/offres/{id}', name: 'app_offer_detail')]
    public function detail(EntityManagerInterface $entityManager, Request $request, ManagerRegistry $doctrine, Applications $applications, Offer $oneOffer): Response
    {
        $oneOffer = $doctrine->getRepository(Offer::class)->findOneBy(['id' => $oneOffer->getId()]);
        $duration = $oneOffer->getDuration();
        $dateInterval = 0;
        if($duration != null)
        {
            $years = $duration->format('%Y années');
            $month = $duration->format('%M mois');
            $days = $duration->format('%D jours');
            $dateInterval = $days . " " . $month . " " . $years . " ";
        }
        
        

        if(!$this->getUser() || !in_array("ROLE_BUSINESS",$this->getUser()->getRoles()))
        {
            $oneOffer->setViews($oneOffer->getViews() + 1);
            $entityManager->persist($oneOffer);
            $entityManager->flush();
        }
        
        if ($request->isMethod('POST'))
        {
            if ($this->isGranted("ROLE_USER"))
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
    #[Route('pro/offres/deposer', name: 'app_offer_create')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $offer = new Offer();

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            if($offer->getstartAt() != null && $offer->getEndAt())
            {
                $offer->setDuration(date_diff($offer->getstartAt(),$offer->getEndAt()));
            }

            $offer->setUser($this->getUser());
            
            
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute("app_offer_detail", [ "id" => $offer->getId() ]);
        }

        return $this->render('business/offer/create.html.twig',
        [
            'Offer' => $form->createView(),
        ]);
    }

    #[IsGranted("ROLE_BUSINESS")]
    #[Route('pro/mes-offres', name: 'app_business_offer_list')]
    public function myOffers(Request $request, ManagerRegistry $doctrine): Response
    {
        return $this->render('business/offer/list.html.twig',
    [
        "offres_archived" => $doctrine->getRepository(Offer::class)->findBy(["isArchived" => 1,"user" => $this->getUser()]) 
    ]);
    }

    #[IsGranted("ROLE_BUSINESS")]
    #[Route('pro/offres/{id}/modifier', name: 'app_offer_edit')]
    public function edit(Request $request, ManagerRegistry $doctrine, Offer $oneOffer): Response
    {
        if($this->getUser() === $oneOffer->getUser())
        {
            if(!$oneOffer->IsArchived())
            {
                $entityManager = $doctrine->getManager();
                $form = $this->createForm(OfferType::class, $oneOffer);
                $form->handleRequest($request);
                if($form->isSubmitted() && $form->isValid())
                {
                    $oneOffer->setUser($this->getUser());
                    if($oneOffer->getstartAt() != null && $oneOffer->getEndAt())
                    {
                        $oneOffer->setDuration(date_diff($oneOffer->getstartAt(),$oneOffer->getEndAt()));
                    }
                    $oneOffer->setCreatedAt(new DateTimeImmutable());
                    $oneOffer = $form->getData();
                    $entityManager->flush();
                    return $this->redirectToRoute("app_offer_detail",
                    [
                        'id' => $oneOffer->getId()
                    ]);
                }
                return $this->render('business/offer/create.html.twig', 
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
        if($oneOffer->getUser() === $this->getUser())
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

<?php

namespace App\Controller\Business;

use App\Entity\Demands;
use App\Entity\Offer;
use App\Entity\User;
use App\Form\User\Edit\BusinessEditFormType;
use App\Form\User\Edit\PersonEditFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/pro/dashboard', name: 'app_business_dashboard')]
    #[IsGranted('ROLE_BUSINESS')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $offers = $doctrine->getManager()->getRepository(Offer::class)->findAll();
        //Nombre de demandes au total (tout les offres) pour une entreprise
        $nbr_demands = 0;
        foreach($this->getUser()->getOffers() as $offer)
        {
            $nbr_demands+= count($offer->getDemands());
        }

        //Nombre de vues
        $nbr_views = 0;
        foreach($this->getUser()->getOffers() as $offer)
        {
            $nbr_views+= $offer->getViews();
        }

        return $this->render('business/dashboard/index.html.twig', [
            'nbr_demands' => $nbr_demands,
            'nbr_views' => $nbr_views, 
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/pro/profil', name: 'app_business_profile_edit')]
    #[IsGranted('ROLE_BUSINESS')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(BusinessEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('user')['profilePicture']->getData();
            if (isset($image)) {

                // Move the file to the directory where files are stored
                $imageName = md5($user->getEmail()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('profile_pictures_directory'),
                    $imageName
                );

                $user->setProfilePicture($imageName);
            }

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('business/dashboard/editer.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/pro/profil-public', name: 'app_business_profile_public')]
    public function profilePublic(Request $request, EntityManagerInterface $entityManager): Response
    {
        $business = $this->getUser();
        $offers = $entityManager->getRepository(Offer::class)->findBy(["user" => $business->getId()]);
        return $this->render('business/compte.html.twig',[
            "business" => $business,
            "offers" => $offers,
        ]);
    }
}

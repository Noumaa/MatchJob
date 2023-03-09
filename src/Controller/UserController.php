<?php

namespace App\Controller;

use App\Entity\Demands;
use App\Entity\Notification;
use App\Entity\Offer;
use App\Entity\User;
use App\Form\User\Edit\BusinessEditFormType;
use App\Form\User\Edit\PersonEditFormType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/notifications', name: 'app_notifications')]
    #[IsGranted('ROLE_USER')]
    public function notifications(ManagerRegistry $doctrine): Response
    {
        $notifs = $doctrine->getRepository(Notification::class)->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('user/notification.html.twig', [
            'notifs' => $notifs
        ]);
    }


    #[Route('/notifications/{id}', name: 'app_notifications_read')]
    #[IsGranted('ROLE_USER')]
    public function readNotification(ManagerRegistry $doctrine, Notification $notification): Response
    {
        $notification->setReadAt(new \DateTimeImmutable('now'));
        $doctrine->getManager()->flush();

        $response = new Response();
        $response->setStatusCode(200);
        return $response;
    }


    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function dashboard(ManagerRegistry $doctrine): Response
    {
        if (in_array('ROLE_BUSINESS', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_business_dashboard');
        }

        return $this->render('person/dashboard/index.html.twig');
    }

    #[Route('/applications', name: 'app_applications')]
    #[IsGranted('ROLE_PERSON')]
    public function applications(): Response
    {
        return $this->render('person/dashboard/applications.html.twig');
    }


    /**
     * Cette fonction gère le formulaire d'édition de l'utilisateur.
     * Il détermine si l'utilisateur est une entreprise ou une personne et lui renvoie le formulaire adapté. 
     * Si le formulaire est valide, l'image séléctionné est alors uploadé dans le répertoire désigné et le nom du fichier est attribué à l'utilisateur.
     * Pour finir, elle renvoie la même vue pour plus amples modifications.
     * 
     * @param Request $request - an instance of the Request object that contains the incoming HTTP request
     * @param EntityManagerInterface $entityManager - an instance of the EntityManager that manages entities and manages the database
     * 
     * @return Response - a response object that redirects the user to the profile page or returns a view for the edit form
     */
    #[Route('/editer', name: 'app_profile_edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $formType = $this->isGranted("ROLE_BUSINESS") ? 
            BusinessEditFormType::class :
            PersonEditFormType::class;

        $form = $this->createForm($formType, $user);
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
        
        if(in_array("ROLE_BUSINESS",$this->getUser()->getRoles()))
        {
            return $this->render('business/editer.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->render('person/editer.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

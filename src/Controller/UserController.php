<?php

namespace App\Controller;

use App\Form\User\BusinessFormType;
use App\Form\User\Edit\PersonEditFormType;
use App\Form\User\PersonFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compte')]
class UserController extends AbstractController
{
    #[Route('', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {        
        return $this->render('user/compte.html.twig');
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

        $formType = in_array("BUSINESS", $user->getRoles()) ? 
            BusinessFormType::class :
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

        return $this->render('user/editer.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

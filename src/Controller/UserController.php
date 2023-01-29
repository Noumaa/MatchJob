<?php

namespace App\Controller;

use App\Form\User\BusinessFormType;
use App\Form\User\IndividualDataType;
use App\Form\User\PersonFormType;
use App\Form\User\UserFormType;
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

    #[Route('/editer', name: 'app_profile_edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $formType = in_array("BUSINESS", $user->getRoles()) ? 
            BusinessFormType::class :
            PersonFormType::class;

        $form = $this->createForm($formType, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('user/editer.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

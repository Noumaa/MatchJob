<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User\IndividualDataType;
use App\Form\User\UserType;
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
        if (!$this->isGranted('ROLE_COMPLETE_USER'))
            return $this->redirectToRoute("app_profile_edit");
        
        return $this->render('user/compte.html.twig', [
            // 'roles' => $this->getUser()->getRoles(),
        ]);
    }

    #[Route('/editer', name: 'app_profile_edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(IndividualDataType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $user->addRole("ROLE_COMPLETE_USER");

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('user/editer.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

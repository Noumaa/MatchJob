<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\User\UserInfoType;
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
    #[Route('', name: 'app_user')]
    #[IsGranted('ROLE_USER')]
    public function account(): Response
    {
        if (!$this->isGranted('ROLE_COMPLETE_USER'))
            return $this->redirectToRoute("app_edit_userinfo");
        
        return $this->render('user/compte.html.twig', [
            // 'roles' => $this->getUser()->getRoles(),
        ]);
    }

    #[Route('/editer', name: 'app_edit_userinfo')]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userInfo = $this->getUser()->getUserInfo() == null ? new UserInfo() : $this->getUser()->getUserInfo();
        $form = $this->createForm(UserInfoType::class, $userInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userInfo->setUser($this->getUser());

            $entityManager->persist($userInfo);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/editer.html.twig', [
            'form' => $form->createView(),
            // 'debug' => $form->get('userInfo')->getData()
        ]);

        // return $this->render('user/editer.html.twig', [
        //     // 'roles' => $this->getUser()->getRoles(),
        // ]);
    }
}

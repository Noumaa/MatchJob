<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\User\UserInfoType;
use App\Form\User\UserType;
use App\Repository\UserInfoRepository;
use Doctrine\Persistence\ManagerRegistry;
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
    public function account(ManagerRegistry $doctrine): Response
    {
        if (!$this->isGranted('ROLE_COMPLETE_USER'))
        {
            return $this->redirectToRoute("app_edit_userinfo");
        }
        $user = $this->getUser();
        $userAccountDetails = $doctrine->getRepository(UserInfo::class)->findOneBy(['id'=>$user->getId()]);
        return $this->render('user/compte.html.twig', 
        [
            'userAccountDetails' => $userAccountDetails,
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

            return $this->redirectToRoute('app_user');
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

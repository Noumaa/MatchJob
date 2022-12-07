<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function edit(): Response
    {
        return $this->render('user/editer.html.twig', [
            // 'roles' => $this->getUser()->getRoles(),
        ]);
    }
}

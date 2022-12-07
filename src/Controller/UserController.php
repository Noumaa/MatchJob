<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/compte', name: 'app_user')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('user/compte.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}

<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    // TODO

    #[Route('/dashboard', name: 'app_person_dashboard')]
    #[IsGranted('ROLE_PERSON')]
    public function person(): Response
    {
        return $this->render('dashboard/person/index.html.twig');
    }

    #[Route('/pro/dashboard', name: 'app_business_dashboard')]
    #[IsGranted('ROLE_BUSINESS')]
    public function business(): Response
    {
        return $this->render('dashboard/business/index.html.twig');
    }
}

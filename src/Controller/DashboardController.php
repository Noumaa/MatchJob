<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/pro/dashboard', name: 'app_business_dashboard')]
    #[IsGranted('ROLE_BUSINESS')]
    public function businessDashboard(): Response
    {
        // TODO stats
        return $this->render("dashboard/business/index.html.twig", [
            'nbr_demands' => 0,
            'nbr_views' => 0,
        ]);
    }

    #[Route('/dashboard', name: 'app_person_dashboard')]
    #[IsGranted('ROLE_PERSON')]
    public function personDashboard(): Response
    {
        return $this->render("dashboard/person/index.html.twig", [

        ]);
    }
}

<?php

namespace App\Controller\Business;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/pro/dashboard', name: 'app_business_dashboard')]
    #[IsGranted('ROLE_BUSINESS')]
    public function index(): Response
    {
        return $this->render('business/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}

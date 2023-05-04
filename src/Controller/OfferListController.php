<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferListController extends AbstractController
{
    #[Route('/offres', name: 'app_offer_list')]
    public function index(): Response
    {
        return $this->render('offer/list.html.twig');
    }
}

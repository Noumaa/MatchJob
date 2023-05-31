<?php

namespace App\Controller;

use App\Entity\Offer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'app_sitemap', format: "xml")]
    public function sitemap(Request $request, ManagerRegistry $doctrine): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        // On initialise un tableau pour lister les URLs
        $urls = [];

        // On ajoute les URLs "statiques"
        $urls[] = [
            'loc' => $this->generateUrl('app_home'),
            'priority' => 1];
        $urls[] = [
            'loc' => $this->generateUrl('app_offer_list'),
            'priority' => 0.8];
        $urls[] = [
            'loc' => $this->generateUrl('app_login'),
            'priority' => 0.8];
        $urls[] = [
            'loc' => $this->generateUrl('app_register'),
            'priority' => 0.7];
        $urls[] = [
            'loc' => $this->generateUrl('app_register_business'),
            'priority' => 0.7];

        // On ajoute les URLs dynamiques des articles dans le tableau
        foreach ($doctrine->getRepository(Offer::class)->findAll() as $offer) {
//            $images = [
//                'loc' => '/uploads/images/featured/'.$offer->getFeaturedImage(), // URL to image
//                'title' => $offer->getTitre()    // Optional, text describing the image
//            ];

            $urls[] = [
                'loc' => $this->generateUrl('app_offer_detail', [
                    'id' => $offer->getId(),
                ]),
                'lastmod' => $offer->getUpdatedAt()->format('Y-m-d'),
//                'image' => $images
            ];
        }

        // Fabrication de la réponse XML
        $response = new Response(
            $this->renderView('sitemap/index.html.twig', ['urls' => $urls,
                'hostname' => $hostname]),
            200
        );

        // Ajout des entêtes
        $response->headers->set('Content-Type', 'text/xml');

        // On envoie la réponse
        return $response;
    }
}

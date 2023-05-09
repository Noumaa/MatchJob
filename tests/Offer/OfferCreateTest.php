<?php

namespace App\Tests\Offer;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OfferCreateTest extends WebTestCase
{
    public function createOffer(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['email' => 'contact@ikea.fr']);
        $client->loginUser($user);

        $client->followRedirects();
        $crawler = $client->request('GET', '/pro/deposer-une-offre');

        $this->assertResponseIsSuccessful("L'utilisateur n'a pas accés au formulaire.");

        $form = $crawler
            ->selectButton('Déposer l\'offre d\'emploi')
            ->form();

        $form['offer_form[label]'] = 'Annonce test';
        $form['offer_form[moneyPerHour]'] = 15;
        $form['offer_form[description]'] = 'Lorem ipsum description tah les ouf venez travailler avec moi svp.';

        $client->submit($form);

        $title = $client->getCrawler()->filter('title')->innerText();

        $this->assertResponseIsSuccessful();
        $this->assertTrue($title == "Annonce test / Ikea", "Le formulaire a redirigé vers la mauvaise offre.");
    }
}

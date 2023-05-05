<?php

namespace App\DataFixtures;

use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Exception;

class OfferFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $users = UserFixtures::$users;

        $offers = 
        [
            ["Assistant(e) en gestion de projets institutionnels H/F",10,"_LIM (Leather in Motion) est un groupe français spécialisé dans la conception, la fabrication et la distribution de selles d’équitation sur mesure haut de gamme. Il compte aujourd’hui une vingtaine de filiales basée en Europe et en Amérique du Nord. Ses marques DEVOUCOUX, CWD et BUTET, partenaires des meilleurs cavaliers internationaux et à la pointe de la technologie, font de LIM Group un sellier reconnu mondialement.","2023-02-01",'2023-03-01'],
            ["Architecte Logiciel H/F",20,"GFP_tech, éditeur majeur du marché du progiciel de gestion santé & prévoyance, recherche activement ses futurs collaborateurs passionnés pour rejoindre l'aventure et participer à la réussite de notre projet d'entreprise ! PO, BA, Product Managers, Développeurs… : Devenez GFP_tech'ers !","2023-02-01",'2023-03-01'],
            ["Business Analyste F/H - CDI",15,"Tu es une personne passionnée par les gens, par le business et par l’objectif général de IKEA, et tu mets tout en œuvre pour améliorer les résultats. Tu es déterminé à renforcer la valeur ajoutée dont bénéficie le client et à contribuer à la croissance et à la prospérité du business par le biais d’une démarche axée sur la collaboration, le leadership, et le développement des personnes. Ce poste est fait pour toi.","2023-02-01",'2023-03-01'],
            ["Equipier(ère) H/F",10,"Devenir salarié.e McDo, c’est avoir la possibilité d’adapter son rythme de travail à son emploi du temps","2023-02-01",'2023-03-01'],
            ["Equipier(ère) H/F",10,"Devenir salarié.e Intermarché, c’est avoir la possibilité d’adapter son rythme de travail à son emploi du temps","2023-02-01",'2023-03-01'],
        ];

        for($i = 0 ; $i<sizeof($offers) ; $i++)
        {
            $offer = new Offer();
            $offer->setLabel($offers[$i][0]);
            $offer->setMoneyPerHour($offers[$i][1]);
            $offer->setDescription($offers[$i][2]);
            $offer->setCreatedAt(new \DateTimeImmutable());
            $offer->setUser($users[$i]);
            $duration = (new \DateTime(($offers[$i][3])))->diff(new \DateTime($offers[$i][4]));
            $offer->setDuration($duration);
            
            $manager->merge($offer);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}

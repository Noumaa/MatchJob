<?php

namespace App\Service\Applications;

use App\Entity\Demands;
use App\Entity\DemandStatusChange;
use App\Entity\Offer;
use App\Entity\User;
use App\Service\Notifications\Notification;
use App\Service\Notifications\NotificationBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class Applications
{
    private ObjectManager $entityManager;
    private NotificationBuilder $notification;

    public function __construct(ManagerRegistry $doctrine, NotificationBuilder $notification)
    {
        $this->entityManager = $doctrine->getManager();
        $this->notification = $notification;
    }

    private function getStatus(ApplicationStatus $status): \App\Entity\DemandStatus
    {
        return $this->entityManager->getRepository(\App\Entity\DemandStatus::class)->findOneBy(['label' => $status->value]);
    }

    public function create(Offer $offer, User $applier): bool
    {
        $demands = [];
        foreach ($offer->getDemands() as $d) {
            if ($d->getIndividual() === $applier) $demands[] = $d;
        }

//        if (!empty($demands)) {
//            $messageFalse = "<strong>Erreur !</strong> Vous avez déjà déposé votre candidature !";
//            return $this->render('offer/detail.html.twig',
//                [
//                        'message' => $message,
//                        'messageType' => 'danger',
//                ]);
//        }

        $demand = new Demands();
        $demand->setOffer($offer);
        $demand->setIndividual($applier);

        $statusChange = new DemandStatusChange();
        $statusChange->setDemand($demand);
        $statusChange->setDemandStatus($this->getStatus(ApplicationStatus::WAITING));

        $this->entityManager->persist($demand);
        $this->entityManager->persist($statusChange);

        $this->entityManager->flush();

        $this->notification
            ->setType(Notification::BusinessNewApplication)
            ->setDemand($demand)
            ->sendTo(array($offer->getUser()));

        return true;
    }
}
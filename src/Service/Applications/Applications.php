<?php

namespace App\Service\Applications;

use App\Entity\Demands;
use App\Entity\DemandStatus;
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

    public function getStatus(ApplicationStatus $status): DemandStatus
    {
        return $this->entityManager
            ->getRepository(DemandStatus::class)
            ->findOneBy([
                'label' => $status->value
            ]);
    }

    public function create(Offer $offer, User $applicant): bool
    {
        $demands = [];
        foreach ($offer->getDemands() as $d) {
            if ($d->getApplicant() === $applicant) $demands[] = $d;
        }

//        if (!empty($demands)) {
//            $messageFalse = "<strong>Erreur !</strong> Vous avez déjà déposé votre candidature !";
//            return $this->render('offer/detail.html.twig',
//                [
//                        'message' => $message,
//                        'messageType' => 'danger',
//                ]);
//        }

        $status = $this->getStatus(ApplicationStatus::WAITING);

        $demand = new Demands();
        $demand->setOffer($offer);
        $demand->setApplicant($applicant);
        $demand->setStatus($status);

        $statusChange = new DemandStatusChange();
        $statusChange->setDemand($demand);
        $statusChange->setDemandStatus($status);

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
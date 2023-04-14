<?php

namespace App\Service\Applications;

use App\Entity\DemandStatus;
use Doctrine\Persistence\ManagerRegistry;

enum ApplicationStatus: string
{
    case WAITING = 'En attente';
    case REJECTED = 'Rejeté';
    case ACCEPTED = 'Accepté';
}
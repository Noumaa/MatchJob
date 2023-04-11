<?php

namespace App\Service\Applications;

enum ApplicationStatus: string
{
    case WAITING = 'En attente';
    case REJECTED = 'Rejeté';
    case ACCEPTED = 'Accepté';
}
<?php

namespace App\Service;

use App\Entity\Establishment;

interface EstablishmentService
{
    public function findDistinctEstablishments(): array;
}
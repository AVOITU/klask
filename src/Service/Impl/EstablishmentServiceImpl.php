<?php

namespace App\Service\Impl;

use App\Repository\EstablishmentRepository;
use App\Service\EstablishmentService;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

// voir EstablishmentService : aucun appelant en prod égalzment
#[AsAlias]
readonly class EstablishmentServiceImpl implements EstablishmentService
{
    public function __construct(
        private EstablishmentRepository $establishmentRepository,
    ) {
    }

    public function findDistinctEstablishments(): array
    {
        return $this->establishmentRepository->findDistinctEstablishments();
    }
}

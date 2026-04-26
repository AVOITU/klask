<?php

namespace App\Service\Impl;


use App\Repository\EstablishmentRepository;
use App\Service\EstablishmentService;


readonly class EstablishmentServiceImpl implements EstablishmentService
{
    public function __construct(
        private EstablishmentRepository $establishmentRepository
    ) {}

    public function findDistinctEstablishments(): array {
        return $this->establishmentRepository->findDistinctEstablishments();
    }
}

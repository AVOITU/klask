<?php

namespace App\Service\Impl;

use App\Repository\Impl\EstablishmentRepositoryImpl;
use App\Service\EstablishmentService;


class EstablishmentServiceImpl implements EstablishmentService
{
    public function __construct(
        private readonly EstablishmentRepositoryImpl $establishmentRepository
    ) {}

    public function findDistinctEstablishments(): array {
        return $this->establishmentRepository->findDistinctEstablishments();
    }
}

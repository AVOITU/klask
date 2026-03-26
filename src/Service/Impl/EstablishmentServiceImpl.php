<?php

namespace App\Service\Impl;


use App\Repository\EstablishmentRepository;
use App\Service\EstablishmentService;


class EstablishmentServiceImpl implements EstablishmentService
{
    public function __construct(
        private readonly EstablishmentRepository $establishmentRepository
    ) {}

    public function findDistinctEstablishments(): array {
        return $this->establishmentRepository->findDistinctEstablishments();
    }
}

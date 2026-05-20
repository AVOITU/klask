<?php

namespace App\Service\Impl;

use App\Entity\Authority;
use App\Repository\AuthorityRepository;
use App\Service\AuthorityService;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

// voir AuthorityService : aucun appelant en prod
// #[AsAlias] = branchement automatique interface càd implémentation, sans configuration YAML à ajouter
#[AsAlias]
class AuthorityServiceImpl implements AuthorityService
{
    public function __construct(
        private readonly AuthorityRepository $authorityRepository,
    ) {
    }

    public function findByRole(string $role): ?Authority
    {
        return $this->authorityRepository->findByRole($role);
    }
}

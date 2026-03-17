<?php

namespace App\Service\Impl;

use App\Entity\Authority;
use App\Repository\AuthorityRepository;
use App\Service\AuthorityService;

require_once __DIR__ . '/../../../vendor/autoload.php';
class AuthorityServiceImpl implements AuthorityService
{
    private AuthorityRepository $authorityRepository;
    /**
     * @param AuthorityRepository $authorityRepository
     */
    public function __construct(AuthorityRepository $authorityRepository)
    {
        $this->authorityRepository = $authorityRepository;
    }

    public function findByRole(string $role) : ?Authority
    {
        return $this->authorityRepository->findByRole($role);
    }
}

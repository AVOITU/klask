<?php

namespace App\Service;

use App\Entity\Authority;

// Aucun contrôleur n'appelle AuthorityService.
// InscriptionServiceImpl injecte AuthorityRepository directement.
// À conserver si un service métier authority est prévu.
interface AuthorityService
{
    public function findByRole(string $role) : ?Authority;
}

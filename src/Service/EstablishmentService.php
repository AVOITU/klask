<?php

namespace App\Service;

// Aucun contrôleur ni service n'appelle EstablishmentService en production.
// L'inscription charge les établissements via EntityType directement.
// À conserver si une couche service dédiée est voulue plus tard.
interface EstablishmentService
{
    public function findDistinctEstablishments(): array;
}

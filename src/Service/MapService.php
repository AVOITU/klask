<?php

namespace App\Service;

interface MapService
{
    /**
     * Récupère les sphères et calcule leurs positions et tailles pour la vue.
     * * @return array<int, array> Tableau contenant les données formatées pour la carte
     */
    public function getPreparedSpheres(): array;
}
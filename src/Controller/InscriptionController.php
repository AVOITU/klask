<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;

interface InscriptionController
{
    public function showInscriptionForm(): Response;

    public function inscriptionSubmit(): Response;
}

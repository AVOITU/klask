<?php

namespace App\Controller;
use App\Entity\Classroom;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface InscriptionController
{
    public function showInscriptionForm(Request $request): Response;

    public function inscriptionSubmit(Request $request): Response;
}

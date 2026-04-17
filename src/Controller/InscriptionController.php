<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface InscriptionController
{
    public function show(): Response;

    public function establishmentChange(Request $request): Response;

    public function regeneratePseudo(Request $request): Response;

    public function save(Request $request): Response;
}

<?php

namespace App\Controller;
use App\Entity\Classroom;
use App\Entity\User;
use App\Repository\Impl\ClassroomRepositoryImpl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface InscriptionController
{
    public function show(): Response;

    public function schoolChange(Request $request): Response;

    public function regeneratePseudo(Request $request): Response;

    public function save(Request $request): Response;
}

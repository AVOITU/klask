<?php

namespace App\Controller;
use App\Entity\Classroom;
use App\Entity\User;
use App\Repository\Impl\ClassroomRepositoryImpl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface InscriptionController
{
    public function showInscriptionForm(Request $request): Response;
}

<?php

namespace App\Controller\Impl;

use App\Controller\InscriptionController;
use App\Service\InscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pages', name: 'pages_')]
class InscriptionControllerImpl extends AbstractController implements InscriptionController
{
    public function __construct(private InscriptionService $inscriptionService) {}

    #[Route('/inscription', name: 'inscription')]
    public function showInscriptionForm(): Response
    {
        $schools = $this->inscriptionService->findDistinctSchools();

        $selectedSchool = trim($_POST['ecole'] ?? '');
        $filteredClasses = ($selectedSchool !== '')
            ? $this->inscriptionService->getClassesBySchool($selectedSchool)
            : [];

        $pseudo = trim($_POST['pseudo_choisi'] ?? '');
        $nom_depart = ($pseudo !== '') ? $pseudo : $this->inscriptionService->generateDefaultNickname();

        $messageSuccess = $messageSuccess ?? null;
        $messageError   = $messageError ?? null;

        return $this->render("inscription.html.twig");
    }

    public function inscriptionSubmit(): Response
    {
        $formAction = $_POST['form_action'] ?? 'save';

        if ($formAction === 'regen') {
            $_POST['pseudo_choisi'] = $this->inscriptionService->generateDefaultNickname();
            $this->showInscriptionForm();
            return $this->render("inscription.html.twig");
        }

        if ($formAction === 'schoolChange') {
            $this->showInscriptionForm();
            return $this->render("inscription.html.twig");
        }

        [$messageSuccess, $messageError] = $this->inscriptionService->registerStudent($_POST);
        $this->showInscriptionForm();
        return $this->render("inscription.html.twig");
    }
}

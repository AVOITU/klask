<?php

namespace Controller\Impl;

use Controller\InscriptionController;
use Service\InscriptionService;

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

class InscriptionControllerImpl implements InscriptionController
{
    public function __construct(private InscriptionService $inscriptionService) {}

    public function showForm(): void
    {
        $schools = $this->inscriptionService->getAllSchools();

        $selectedSchool = trim($_POST['ecole'] ?? '');
        $filteredClasses = ($selectedSchool !== '')
            ? $this->inscriptionService->getClassesBySchool($selectedSchool)
            : [];

        $pseudo = trim($_POST['pseudo_choisi'] ?? '');
        $nom_depart = ($pseudo !== '') ? $pseudo : $this->inscriptionService->generateDefaultNickname();

        $messageSuccess = $messageSuccess ?? null;
        $messageError   = $messageError ?? null;

        require __DIR__ . '/../../../templates/formulaireklask.php';
    }

    public function submit(): void
    {
        $formAction = $_POST['form_action'] ?? 'save';

        if ($formAction === 'regen') {
            $_POST['pseudo_choisi'] = $this->inscriptionService->generateDefaultNickname();
            $this->showForm();
            return;
        }

        if ($formAction === 'schoolChange') {
            $this->showForm();
            return;
        }

        [$messageSuccess, $messageError] = $this->inscriptionService->registerStudent($_POST);
        $this->showForm();
    }
}
<?php

require_once __DIR__ . '/../InitialFormController.php';
require_once __DIR__ . '/../../Service/InitialFormService.php';

class InitialFormControllerImpl implements InitialFormController
{
    private InitialFormService $service;

    public function __construct(InitialFormService $service)
    {
        $this->service = $service;
    }

    public function index(): void
    {
        $data = $this->service->getAll();
        include __DIR__ . '/../../../templates/InitialForm.php';
    }
}

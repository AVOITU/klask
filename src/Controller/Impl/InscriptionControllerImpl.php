<?php

namespace App\Controller\Impl;

use App\Controller\InscriptionController;
use App\Entity\User;
use App\Form\InscriptionFormType;
use App\Service\InscriptionService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class InscriptionControllerImpl extends AbstractController implements InscriptionController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly InscriptionService $inscriptionService,
    ) {
    }

    #[Route('/inscription', name: 'app_inscription_show', methods: ['GET'])]
    public function show(): Response
    {
        $user = new User();
        $pseudo = $this->inscriptionService->generateDefaultNickname();

        $form = $this->buildForm(
            user: $user,
            selectedSchool: '',
            pseudo: $pseudo
        );

        return $this->renderForm($form, '', $pseudo);
    }

    #[Route('/inscription/school-change', name: 'app_inscription_school_change', methods: ['POST'])]
    public function schoolChange(Request $request): Response
    {
        $data = $this->getPostedFormData($request);

        $selectedSchool = (string) ($data['school'] ?? '');
        $pseudo = (string) ($data['pseudoUser'] ?? $this->inscriptionService->generateDefaultNickname());

        $user = new User();
        if ($pseudo !== '') {
            $user->setPseudoUser($pseudo);
        }

        $form = $this->buildForm(
            user: $user,
            selectedSchool: $selectedSchool,
            pseudo: $pseudo
        );

        $form->submit($data, false);

        return $this->renderForm($form, $selectedSchool, $pseudo);
    }

    #[Route('/inscription/regen', name: 'app_inscription_regen', methods: ['POST'])]
    public function regeneratePseudo(Request $request): Response
    {
        $data = $this->getPostedFormData($request);

        $selectedSchool = (string) ($data['school'] ?? '');
        $newPseudo = $this->inscriptionService->generateDefaultNickname();

        $user = new User();
        $user->setPseudoUser($newPseudo);

        $form = $this->buildForm(
            user: $user,
            selectedSchool: $selectedSchool,
            pseudo: $newPseudo
        );

        $data['pseudoUser'] = $newPseudo;
        $form->submit($data, false);

        return $this->renderForm($form, $selectedSchool, $newPseudo);
    }

    #[Route('/inscription/save', name: 'app_inscription_save', methods: ['POST'])]
    public function save(Request $request): Response
    {
        $data = $this->getPostedFormData($request);
        $selectedSchool = (string) ($data['school'] ?? '');
        $pseudo = (string) ($data['pseudoUser'] ?? '');

        $user = new User();

        $form = $this->buildForm(
            user: $user,
            selectedSchool: $selectedSchool,
            pseudo: $pseudo
        );

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->renderForm($form, $selectedSchool, $pseudo);
        }

        try {
            $this->inscriptionService->registerStudent($user);

            $this->addFlash('success', 'Utilisateur créé avec succès.');

            return $this->redirectToRoute('app_inscription_show');
        } catch (UniqueConstraintViolationException) {
            $this->addFlash('error', 'Le pseudonyme est déjà pris.');
        } catch (Throwable $e) {
            $this->logger->error('Erreur création utilisateur', [
                'exception' => $e,
            ]);

            $this->addFlash('error', 'Erreur lors de la création.');
        }

        return $this->renderForm($form, $selectedSchool, $pseudo);
    }

    private function buildForm(User $user, string $selectedSchool, string $pseudo): FormInterface
    {
        $schools = $this->inscriptionService->findDistinctEstablishments();

        return $this->createForm(InscriptionFormType::class, $user, [
            'schools' => $schools,
            'selected_school' => $selectedSchool,
            'nom_depart' => $pseudo,
        ]);
    }

    private function renderForm(FormInterface $form, string $selectedSchool, string $pseudo): Response
    {
        return $this->render('inscription/inscription.html.twig', [
            'inscriptionForm' => $form->createView(),
            'selectedSchool' => $selectedSchool,
            'nom_depart' => $pseudo,
        ]);
    }

    private function getPostedFormData(Request $request): array
    {
        return $request->request->all('inscription_form');
    }
}

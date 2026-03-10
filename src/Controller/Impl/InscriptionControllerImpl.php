<?php

namespace App\Controller\Impl;

use App\Controller\InscriptionController;
use App\Entity\User;
use App\Form\InscriptionFormType;
use App\Repository\Impl\ClassroomRepositoryImpl;
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

    #[Route('/inscription', name: 'app_inscription', methods: ['GET', 'POST'])]
    public function showInscriptionForm(Request $request): Response
    {
        $user = new User();
        $schools = $this->inscriptionService->findDistinctSchools();
        $pseudoUser = $this->inscriptionService->generateDefaultNickname();
        $filteredClasses = '';
        $selectedSchool = $this->extractSelectedSchool($request);

        $form = $this->createInscriptionForm($user, $schools, $selectedSchool,
                                             $pseudoUser, $filteredClasses);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleValidForm($form, $user);
        }

        return $this->render('inscription/inscription.html.twig', [
            'inscriptionForm' => $form->createView(),
            'schools' => $schools,
            'selectedSchool' => $selectedSchool,
            'filteredClasses' => $filteredClasses,
            'nom_depart' => $pseudoUser
        ]);
    }

    private function createInscriptionForm(User $user, array $schools, string $selectedSchool,
                                           string $pseudoUser, string $filteredClasses): FormInterface
    {
        return $this->createForm(InscriptionFormType::class, $user, [
            'schools' => $schools,
            'selected_school' => $selectedSchool,
            'filteredClasses' =>$filteredClasses,
            'nom_depart' => $pseudoUser
        ]);
    }

    private function extractSelectedSchool(Request $request): string
    {
        if (!$request->isMethod('POST')) {
            return '';
        }

        $formData = $request->request->all('inscription_form');

        return (string) ($formData['school'] ?? '');
    }

    private function handleValidForm(FormInterface $form, User $student): Response
    {
        try {
            $school = (string) $form->get('school')->getData();

            $this->inscriptionService->registerStudent($student);

            $this->addFlash('success', 'Utilisateur créé avec succès.');

            return $this->redirectToRoute('app_inscription');
        } catch (UniqueConstraintViolationException) {
            $this->addFlash('error', 'Le pseudonyme est déjà pris.');
        } catch (Throwable $e) {
            $this->logger->error('Erreur création utilisateur', [
                'exception' => $e,
            ]);

            $this->addFlash('error', 'Erreur lors de la création.');
        }

        return $this->redirectToRoute('app_inscription');
    }
}

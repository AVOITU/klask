<?php

namespace App\Controller\Impl;

use App\Controller\InscriptionController;
use App\Entity\User;
use App\Form\InscriptionFormType;
use App\Repository\Impl\ClassroomRepositoryImpl;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Exception;

class InscriptionControllerImpl extends AbstractController implements InscriptionController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    #[Route('/inscription', name: 'app_inscription', methods: ['GET', 'POST'])]
    public function showInscriptionForm(
        Request $request,
        ClassroomRepositoryImpl $classroomRepo,
        EntityManagerInterface $em,
    ): Response {
        $user = new User();
        $filteredClasses = '';
        // 1) Load schools (for the "school" ChoiceType, unmapped)
        $schools = $classroomRepo->findDistinctSchools();

        // 2) Detect currently selected school (so the classroom EntityType can be filtered)
        // - First display (GET): none selected
        // - After submit (POST): take it from submitted form data
        if ($request->isMethod('POST')) {
            // form name = "inscription_form" by default? actually it's based on FormType name.
            // safest: read the form root array using $form->getName() AFTER creation.
            $posted = $request->request->all(); // we'll re-read after form creation below
        }

        // 3) Build form (first pass) with empty selected school (safe)
        $form = $this->createForm(InscriptionFormType::class, $user, [
            'schools' => $schools,
            'selected_school' => '',
        ]);

        // 4) Now that we know the real form name, read selected school from POST and rebuild if needed
        $pseudoUser = $form->getName();
        $selectedSchool = '';
        if ($request->isMethod('POST')) {
            $formRoot = $request->request->all($pseudoUser);
            $selectedSchool = (string)($formRoot['school'] ?? '');
        }

        if ($selectedSchool !== '') {
            // rebuild so query_builder filters classrooms correctly
            $form = $this->createForm(InscriptionFormType::class, $user, [
                'schools' => $schools,
                'selected_school' => $selectedSchool,
            ]);
        }

        // 5) Handle request
        $form->handleRequest($request);

        // 6) Persist on valid submit
        try {
            $form->isSubmitted() && $form->isValid();
            // school is unmapped => you can read it if you want
            $school = (string)$form->get('school')->getData();

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_inscription');
        } catch (UniqueConstraintViolationException $e) {
            $this->addFlash('error', 'Le pseudonyme est déjà pris');

        } catch (Exception $e){

            $this->logger->error('Erreur création utilisateur', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->addFlash('error', 'Erreur lors de la création');
        }

        return $this->render('inscription/inscription.html.twig', [
            'inscriptionForm' => $form->createView(),
            'schools' => $schools,
            'selectedSchool' => $selectedSchool,
            'filteredClasses' => $filteredClasses,
            'nom_depart' => $pseudoUser
        ]);
    }
}

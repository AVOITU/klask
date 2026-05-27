<?php

namespace App\Controller;

use App\Entity\Establishment;
use App\Entity\User;
use App\Form\InscriptionFormType;
use App\Service\InscriptionService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class InscriptionController extends AbstractController
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly InscriptionService $inscriptionService,
        private readonly Security $security,
    ) {}

    #[Route('/inscription', name: 'app_inscription_show', methods: ['GET'])]
    public function show(): Response
    {
        $pseudo = $this->inscriptionService->generateUniquePseudo();

        return $this->renderForm($this->buildForm(new User(), $pseudo));
    }

    #[Route('/inscription/save', name: 'app_inscription_save', methods: ['POST'])]
    public function save(Request $request): Response
    {
        $user = new User();
        $form = $this->buildForm($user);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->renderForm($form);
        }

        $establishment = $form->get('establishment')->getData();
        if (!$establishment instanceof Establishment) {
            $this->addFlash('error', 'Veuillez sélectionner un établissement.');
            return $this->renderForm($form);
        }

        $group = $this->inscriptionService->findGroupByCode(trim((string) $user->getGroupCode()));

        if ($group === null) {
            $this->addFlash('error', 'Code de groupe invalide. Vérifiez le code avec votre accompagnateur.');
            return $this->renderForm($form);
        }

        $level = (string) $form->get('groupLevel')->getData();

        if (!$this->inscriptionService->validateGroupForRegistration($group, $establishment, $level)) {
            $this->addFlash('error', 'Ce code de groupe ne correspond pas à votre établissement ou à votre groupe de classe.');
            return $this->renderForm($form);
        }

        if ($this->inscriptionService->isGroupFull($group)) {
            $this->addFlash('error', 'Ce groupe est complet (40 élèves maximum).');
            return $this->renderForm($form);
        }

        $user->setGroup($group);

        try {
            $created = $this->inscriptionService->registerStudent($user);
            $this->security->login($created, 'form_login', 'main');
            $this->addFlash('success', sprintf(
                'Bienvenue, %s ! Tu rejoins le groupe %s (%s).',
                $created->getPseudo(),
                $group->getName() ?? '',
                $establishment->getName()
            ));

            return $this->redirectToRoute('app_bienvenue');
        } catch (Throwable $e) {
            $this->logger->error('Erreur création utilisateur', ['exception' => $e]);
            $this->addFlash('error', 'Erreur lors de la création. Veuillez réessayer.');
        }

        return $this->renderForm($form);
    }

    private function buildForm(User $user, string $pseudo = ''): FormInterface
    {
        return $this->createForm(InscriptionFormType::class, $user, ['nom_depart' => $pseudo]);
    }

    private function renderForm(FormInterface $form): Response
    {
        return $this->render('inscription/inscription.html.twig', [
            'inscriptionForm' => $form->createView(),
        ]);
    }
}

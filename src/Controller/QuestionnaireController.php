<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserSphereRating;
use App\Repository\SphereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class QuestionnaireController extends AbstractController
{
    private const AFFIRMATIONS = [
        'A' => [
            'text' => "J'aime créer de mes mains : Je suis manuel, j'aime transformer la matière, cuisiner ou réparer.",
            'zone' => 'CRÉATIF',
        ],
        'B' => [
            'text' => "Je suis rigoureux : J'aime l'ordre, les règles, la précision et quand tout est bien organisé.",
            'zone' => 'RIGOUREUX',
        ],
        'C' => [
            'text' => "J'aime la nouveauté : Je suis curieux des technologies, du digital, de l'info et de l'innovation.",
            'zone' => 'NOUVEAUTÉ',
        ],
        'D' => [
            'text' => "J'aime être en extérieur : J'ai besoin de bouger, d'être dehors et au contact de la nature ou du terrain.",
            'zone' => 'EXTÉRIEUR',
        ],
        'E' => [
            'text' => "J'aime communiquer : J'aime parler, convaincre, expliquer des choses et rencontrer de nouvelles personnes.",
            'zone' => 'COMMUNIQUER',
        ],
        'F' => [
            'text' => "J'aime me sentir utile : J'ai le sens du service, j'aime soigner, aider et m'occuper des autres.",
            'zone' => 'UTILE',
        ],
    ];

    public function __construct(
        private readonly SphereRepository $sphereRepository,
        private readonly EntityManagerInterface $em,
        private readonly CsrfTokenManagerInterface $csrf, // I3
    ) {
    }

    #[Route('/questionnaire', name: 'app_questionnaire', methods: ['GET'])]
    public function show(): Response
    {
        return $this->render('questionnaire/questionnaire.html.twig', [
            'affirmations' => self::AFFIRMATIONS,
        ]);
    }

    #[Route('/questionnaire/save', name: 'app_questionnaire_save', methods: ['POST'])]
    public function save(Request $request): Response
    {
        // vérification token csrf
        if (!$this->csrf->isTokenValid(new CsrfToken('questionnaire', $request->request->get('_csrf_token')))) {
            $this->addFlash('error', 'Token de sécurité invalide. Rechargez la page et réessayez.');

            return $this->redirectToRoute('app_questionnaire');
        }

        $student = $this->getUser();
        if (!$student instanceof User) {
            return $this->redirectToRoute('app_inscription_show');
        }

        $ratings = $request->request->all('ratings');

        // Validation des 6 notes
        $expectedLetters = array_keys(self::AFFIRMATIONS);
        $submittedValues = [];

        foreach ($expectedLetters as $letter) {
            $value = isset($ratings[$letter]) ? (int) $ratings[$letter] : 0;

            if ($value < 1 || $value > 6) {
                $this->addFlash('error', 'Chaque affirmation doit recevoir une note entre 1 et 6.');

                return $this->redirectToRoute('app_questionnaire');
            }

            $submittedValues[] = $value;
        }

        if (count(array_unique($submittedValues)) !== 6) {
            $this->addFlash('error', 'Tu ne peux utiliser chaque chiffre (1 à 6) qu\'une seule fois.');

            return $this->redirectToRoute('app_questionnaire');
        }

        // Suppression des notes existantes
        $this->em->createQuery('DELETE FROM App\Entity\UserSphereRating r WHERE r.user = :user')
            ->setParameter('user', $student)
            ->execute();

        // Sauvegarde des nouvelles notes
        foreach ($expectedLetters as $letter) {
            $zoneName = self::AFFIRMATIONS[$letter]['zone'];
            $sphere   = $this->sphereRepository->findOneBy(['name' => $zoneName]);

            if ($sphere === null) {
                continue; // Sphère pas encore créée en Bdd
            }

            $this->em->persist(new UserSphereRating($student, $sphere, (int) $ratings[$letter]));
        }

        $this->em->flush();

        return $this->redirectToRoute('app_map');
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\UserRepository;


class BilanController extends AbstractController
{
    /**
     * ROUTE 1 : Affichage du bilan sur une page Web classique
     */
    #[IsGranted('ROLE_STUDENT')] // On sécurise : seuls les élèves y ont accès
    #[Route('/bilan/eleve', name: 'app_bilan_eleve')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // On récupère toutes les données calculées via notre fonction privée (voir plus bas)
        $data = $this->getStudentBilanData($user);

        return $this->render('bilan/eleve.html.twig', $data);
    }

    /**
     * ROUTE 2 : Génération et téléchargement du PDF
     */

    #[IsGranted('ROLE_STUDENT')] // On sécurise : seuls les élèves y ont accès
    #[Route('/bilan/eleve/pdf', name: 'app_bilan_eleve_pdf')]
    public function downloadPdf(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = $this->getStudentBilanData($user);

        // ==========================================
        // 🔒 MODE SÉCURISÉ (PRODUCTION)
        // ==========================================
        /*
        if (!$data['isPdfUnlocked']) {
            $this->addFlash('error', 'Vous devez visiter au moins 3 stands ou participer pendant 1 heure pour débloquer votre bilan.');
            return $this->redirectToRoute('app_bilan_eleve');
        }
        */
        // ==========================================
        // 🔓 MODE TEST : On laisse passer tout le monde
        // ==========================================

        // 1. Configuration de DomPDF
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true); // Autorise DomPDF à charger tes images/CSS externes

        // 2. Initialisation
        $dompdf = new Dompdf($pdfOptions);

        // 3. On génère le code HTML à partir de Twig
        // (On peut utiliser le même fichier Twig que la page web, ou un spécifique pour le PDF)
        $html = $this->renderView('bilan/eleve_pdf.html.twig', $data);

        // 4. On charge le HTML dans DomPDF
        $dompdf->loadHtml($html);

        // 5. Configuration de la taille du papier (A4, portrait)
        $dompdf->setPaper('A4', 'portrait');

        // 6. Rendu du PDF (La compilation)
        $dompdf->render();

        // 7. On récupère le contenu brut du PDF
        $pdfOutput = $dompdf->output();

        // 8. On nettoie le nom du fichier (pour éviter les bugs si le pseudo contient des espaces ou caractères bizarres)
        $safeFilename = "Mon_Bilan_AJE29_" . preg_replace('/[^A-Za-z0-9\-]/', '_', $user->getUserIdentifier()) . ".pdf";

        // 9. On renvoie une VRAIE réponse Symfony
        return new Response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $safeFilename . '"'
        ]);
    }


    /**
     * FONCTION PRIVÉE : Le "Cerveau" qui calcule les points (mutualisé pour le Web et le PDF)
     */
    private function getStudentBilanData(User $user): array
    {
        $scorePerso = 0;
        $scoreGroupe = 0;


        // 1. Récupération des scans de l'élève (Parcours)
        $scans = $user->getScans();

        $isPdfUnlocked = false;
        $firstScanDate = null;


        if (count($scans) >= 3) {
            // Règle 1 : Au moins 3 stands validés
            $isPdfUnlocked = true;
        } elseif (count($scans) > 0) {
            // Règle 2 : 1 heure s'est écoulée depuis le premier scan
            // On cherche la date du tout premier scan
            foreach ($scans as $scan) {
                // Pas sur que ce soit le bon getter mais c'est pour tester avec les fixtures
                $scanDate = $scan->getHourValidation();
                if ($firstScanDate === null || $scanDate < $firstScanDate) {
                    $firstScanDate = $scanDate;
                }
            }

            if ($firstScanDate) {
                $now = new \DateTimeImmutable();
                $diffSeconds = $now->getTimestamp() - $firstScanDate->getTimestamp();

                if ($diffSeconds >= 3600) { // 3600 secondes = 1 heure
                    $isPdfUnlocked = true;
                }
            }
        }

        // 2. Calcul du score personnel
        foreach ($scans as $scan) {
            // On remonte la chaîne : Scan -> Activity -> Category -> nbrPoints
            $scorePerso += $scan->getActivity()->getCategory()->getNbrPoints();
        }

        // 3. Calcul du score du groupe
        $group = $user->getGroup();

        if ($group) {
            foreach ($group->getUsers() as $studentInGroup) {
                foreach ($studentInGroup->getScans() as $studentScan) {
                    $scoreGroupe += $studentScan->getActivity()->getCategory()->getNbrPoints();
                }
            }
        }

        // ==========================================
        // 🔒 MODE SÉCURISÉ (PRODUCTION)
        // ==========================================
        /*
        $isPdfUnlocked = false;
        $firstScanDate = null;
        if (count($scans) >= 3) {
            $isPdfUnlocked = true;
        } elseif (count($scans) > 0) {
            foreach ($scans as $scan) {
                // Remplacer getCreatedAt par le vrai getter de date de ton entité Scan
                $scanDate = $scan->getCreatedAt();
                if ($firstScanDate === null || $scanDate < $firstScanDate) {
                    $firstScanDate = $scanDate;
                }
            }
            if ($firstScanDate) {
                $now = new \DateTimeImmutable();
                if (($now->getTimestamp() - $firstScanDate->getTimestamp()) >= 3600) {
                    $isPdfUnlocked = true;
                }
            }
        }
        */

        // ==========================================
        // 🔓 MODE TEST (DÉVELOPPEMENT)
        // ==========================================
        $isPdfUnlocked = true;
        // ==========================================

        return [
            'user' => $user,
            'group' => $group,
            'scans' => $scans,
            'scorePerso' => $scorePerso,
            'scoreGroupe' => $scoreGroupe,
            'isPdfUnlocked' => $isPdfUnlocked,
        ];
    }

    /**
     * ROUTE 3 : Affichage de la page de Bilan de groupe
     */
    #[Route('/bilan/groupe', name: 'app_bilan_groupe')]
    #[IsGranted('ROLE_ACCOMPANYING')]
    public function groupBilan(UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $groupCode = $user->getGroupCode(); // CORRECTION

        if (!$groupCode) {
            return $this->render('bilan/accompagnateur.html.twig', ['hasGroup' => false]);
        }

        $data = $this->getGroupBilanData($groupCode, $user, $userRepository);
        $data['hasGroup'] = true;
        $data['group'] = $groupCode;

        return $this->render('bilan/accompagnateur.html.twig', $data);
    }

    /**
     * ROUTE 4 : Génération du PDF pour le groupe
     */
    #[Route('/bilan/groupe/pdf', name: 'app_bilan_groupe_pdf')]
    #[IsGranted('ROLE_ACCOMPANYING')]
    public function downloadGroupPdf(UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $groupCode = $user->getGroupCode(); // CORRECTION

        if (!$groupCode) {
            return $this->redirectToRoute('app_accompanying_dashboard');
        }

        $data = $this->getGroupBilanData($groupCode, $user, $userRepository);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView('bilan/accompagnateur_pdf.html.twig', array_merge(['accompanying' => $user, 'group' => $groupCode], $data));

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfOutput = $dompdf->output();
        // On nettoie le nom du fichier (ex: GRP0002)
        $safeGroupName = preg_replace('/[^A-Za-z0-9\-]/', '_', $groupCode);

        return new Response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Bilan_Groupe_' . $safeGroupName . '.pdf"'
        ]);
    }

    /**
     * FONCTION PRIVÉE : Le moteur de calcul pour le groupe
     */
    private function getGroupBilanData(string $groupCode, User $user, UserRepository $userRepository): array
    {
        // CORRECTION : Recherche via groupCode
        $students = $userRepository->findBy(['groupCode' => $groupCode]);
        $totalScore = 0;
        $totalScans = 0;
        $spheresStats = [];
        $activitiesStats = [];

        foreach ($students as $student) {
            if ($student !== $user) {
                foreach ($student->getScans() as $scan) {
                    $activity = $scan->getActivity();
                    $category = $activity->getCategory();

                    $totalScore += $category->getNbrPoints();
                    $totalScans++;

                    $sphereName = $activity->getSphere() ? $activity->getSphere()->getName() : 'Non classé';
                    $spheresStats[$sphereName] = ($spheresStats[$sphereName] ?? 0) + 1;

                    $actName = $activity->getName();
                    if (!isset($activitiesStats[$actName])) {
                        $activitiesStats[$actName] = ['count' => 0, 'category' => $category->getType()];
                    }
                    $activitiesStats[$actName]['count']++;
                }
            }
        }
        uasort($activitiesStats, fn($a, $b) => $b['count'] <=> $a['count']);

        $isPdfUnlocked = true;

        return [
            // Sécurité : On s'assure de ne pas avoir de compte négatif si l'accompagnateur est le seul dans le groupe
            'studentsCount' => max(0, count($students) - 1),
            'totalScore' => $totalScore,
            'totalScans' => $totalScans,
            'spheresStats' => $spheresStats,
            'activitiesStats' => $activitiesStats,
            'isPdfUnlocked' => $isPdfUnlocked,
        ];
    }}

// Pour l'instant pour le fixture, je suis parti sur le groupcode plutot que le groupe, parce qu'il est plus unique et précis.

<?php

namespace Service\Impl;

use Model\ClassRoom;
use Model\User;
use PDOException;
use Security\Role;
use Service\AuthorityService;
use Service\ClassRoomService;
use Service\InscriptionService;
use Service\UserService;

require_once __DIR__ . '/../../../vendor/autoload.php';

class InscriptionServiceImpl implements InscriptionService
{
    private array $ANIMALS = [
        'Tardigrade', 'Loutre', 'Panda', 'Aigle', 'Renard', 'Loup', 'Hibou',
        'Dauphin', 'Faucon', 'Lynx', 'Salamandre', 'Koala',
        'Suricate', 'Ours', 'Lémurien', 'Ornithorynque', 'Caméléon', 'Iguane',
        'Jaguar', 'Panthère', 'Requin', 'Baleine', 'Orque',
        'Hamster', 'Castor', 'Hérisson', 'Ecureuil', 'Kangourou', 'Lama', 'Zèbre',
        'Dragon', 'Phoenix', 'Griffon', 'Pégase', 'Sphinx', 'Yéti', 'Kraken',
        'Chimère', 'Hydre', 'Titan', 'Cyclope', 'Gargouille', 'Licorne'
    ];

    private array $ADJECTIVES = [
        'Cosmique', 'Galactique', 'Solaire', 'Lunaire', 'Stellaire', 'Polaire',
        'Volcanique', 'Aquatique', 'Electrique', 'Magnétique', 'Bionique', 'Cyber',
        'Intrépide', 'Brave', 'Sage', 'Zen', 'Fidèle', 'Rebelle', 'Sauvage',
        'Libre', 'Solitaire', 'Sympathique', 'Drôle', 'Excentrique', 'Artiste',
        'Habile', 'Agile', 'Rapide', 'Véloce', 'Tenace', 'Robuste', 'Stoïque',
        'Diplomate', 'Pacifique', 'Terrible', 'Redoutable', 'Invincible',
        'Invisible', 'Mystique', 'Magique', 'Enigmatique', 'Fantastique',
        'Légendaire', 'Mythique', 'Héroïque', 'Epique', 'Titanesque',
        'Incroyable', 'Imprévisible', 'Inarrêtable', 'Insaisissable'
    ];

    public function __construct(
        private ClassRoomService $classRoomService,
        private UserService $userService,
        private AuthorityService $authorityService
    ) { }

    public function getAllSchools(): array {
        return $this->classRoomService->findAllSchools();
    }

    public function getClassesBySchool($school): array {
        return $this->classRoomService->findClassesBySchool($school);
    }

    public function generateDefaultNickname(): string
    {
        $animal = $this->ANIMALS[array_rand($this->ANIMALS)];
        $adjectif = $this->ADJECTIVES[array_rand($this->ADJECTIVES)];
        return $animal . ' ' . $adjectif;
    }

    public function registerStudent(array $post): array
    {
        $messageSuccess = null;
        $messageError   = null;

        $classId = isset($post['classe_final_id']) ? (int)$post['classe_final_id'] : 0;
        $pseudo  = isset($post['pseudo_choisi'])
            ? htmlspecialchars($post['pseudo_choisi'], ENT_QUOTES, 'UTF-8')
            : 'Anonyme';

        if ($classId <= 0) {
            return [null, 'Classe invalide.'];
        }

        try {
            /** @var ClassRoom|null $classRoom */
            $classRoom = $this->classRoomService->findById($classId);

            if ($classRoom === null) {
                return [null, 'Classe introuvable.'];
            }

            $authority =  $this->authorityService->findByRole(Role::STUDENT->value);

            $student = new User(
                validations: [],
                authority: $authority,
                idUser: (int)null,
                pseudoUser: $pseudo,
                classRoom: $classRoom
            );

            $this->userService->insertStudent($student);

            $messageSuccess = [
                'ecole'  => $classRoom->getSchool(),
                'classe' => $classRoom->getClassName(),
                'pseudo' => $student->getPseudoUser(),
            ];

        } catch (PDOException $e) {
            $messageError = ($e->getCode() === '23000')
                ? '⚠️ Ce pseudo est déjà pris ! Relancez le dé.'
                : 'Erreur lors de l’inscription.';
        }
        return [$messageSuccess, $messageError];
    }
}
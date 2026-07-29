<?php

namespace App\Controller\Admin;

use App\Entity\Notification;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class NotificationCrudController extends AbstractCrudController
{
    public function __construct(private HubInterface $hub) {} // Interface Mercure ? (à voir)

    public static function getEntityFqcn(): string
    {
        return Notification::class;
    }

    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        // On sauvegarde d'abord en base de données (le comportement normal)
        parent::persistEntity($entityManager, $entityInstance);

        // Ensuite, on vérifie que c'est bien une Notification et qu'elle est "Active"
        if ($entityInstance instanceof Notification && $entityInstance->isActive()) {

            // On prépare le paquet de données pour le pop-up
            $data = json_encode([
                'title' => $entityInstance->getTitle(),
                'message' => $entityInstance->getMessage(),
                'colorCode' => $entityInstance->getColorCode()
            ]);

            // On l'envoie dans le tuyau "klask/notifications"
            $update = new Update('klask/notifications', $data);
            $this->hub->publish($update);
        }
    }

    #[AdminRoute]
    public function sendPushAction(AdminContext $context, AdminUrlGenerator $adminUrlGenerator): Response
    {
        // Fonction pas encore finie (le mercure ne reussit pas à envoyer)
        //  On récupère la notification sur laquelle on a cliqué
        /** @var Notification $notification */
        $notification = $context->getEntity()->getInstance();

        $fallbackUrl = $adminUrlGenerator->setController(NotificationCrudController::class)->setAction('index')->generateUrl();

        //  Sécurité : on empêche d'envoyer si elle est désactivée (optionnel)
        if (!$notification->isActive()) {
            $this->addFlash('warning', 'Impossible d\'envoyer le pop-up : la notification est inactive.');
            // On redirige vers l'index si ça échoue
            return $this->redirect($fallbackUrl);
        }

        //  On prépare le paquet de données
        $data = json_encode([
            'title' => $notification->getTitle(),
            'message' => $notification->getMessage(),
            'colorCode' => $notification->getColorCode(),
            'startTime' => $notification->getStartTime() ? $notification->getStartTime()->format('c') : null,
            'endTime' => $notification->getEndTime() ? $notification->getEndTime()->format('c') : null,
            'actionText' => $notification->getActionText(),
            'actionType' => $notification->getActionType(),
        ]);

        //  On publie le Pop-up en temps réel (Mercure)
        $update = new Update('klask/notifications', $data);
        $this->hub->publish($update);

        //  On affiche un petit message de succès vert en haut d'EasyAdmin
        $this->addFlash('success', '🚀 Le pop-up "' . $notification->getTitle() . '" a été envoyé à tous les utilisateurs connectés !');

        //  On redirige proprement vers la page où l'on était
        return $this->redirect($fallbackUrl);
    }

    public function configureActions(Actions $actions): Actions
    {
        $sendPushAction = Action::new('sendPush', 'Déclencher Pop-up', 'fas fa-paper-plane')
            ->linkToCrudAction('sendPushAction') // Le nom de la méthode à appeler en bas
            ->setCssClass('text-success fw-bold'); // Un peu de style (vert et gras)

        return $actions
            // On ajoute le bouton sur la page qui liste toutes les notifications
            ->add(Crud::PAGE_INDEX, $sendPushAction)
            // Optionnel : On peut aussi l'ajouter sur la page de détail d'une notification
            ->add(Crud::PAGE_DETAIL, $sendPushAction);

    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title', 'Titre de la notification');

        yield TextareaField::new('message', 'Message')
            ->setHelp('Soyez clair et concis.');

        yield ChoiceField::new('colorCode', 'Couleur')->setChoices([
            '🔴 Rouge (Urgence)' => '#dc3545',
            '🟠 Orange (Avertissement)' => '#fd7e14',
            '🔵 Bleu (Information)' => '#0d6efd',
            '🟢 Vert (Succès/Ouverture)' => '#198754',
        ]);

        yield BooleanField::new('isActive', 'Notification Active')
            ->setHelp('Si coché, la notification apparaîtra sur la carte.');

        yield DateTimeField::new('startTime', 'Heure de début')
            ->setRequired(false) // C'est ça qui le rend optionnel !
            ->setHelp('Laissez vide pour un affichage immédiat sans compte à rebours.');

        yield DateTimeField::new('endTime', 'Heure de fin')
            ->setRequired(false)
            ->setHelp('Optionnel. Heure à laquelle l\'événement se termine.');

        yield TextField::new('actionText', 'Texte du bouton d\'action')
            ->setHelp('Laissez vide si vous ne voulez pas de bouton supplémentaire.')
            ->setRequired(false);

        yield ChoiceField::new('actionType', 'Action du bouton')
            ->setChoices([
                'Fermer la notification' => 'close',
                'Ouvrir la sidebar' => 'open_sidebar',
                'Afficher un chemin sur la carte' => 'show_path',
            ])
            ->setRequired(false);
    }
}

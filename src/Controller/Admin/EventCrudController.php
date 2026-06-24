<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Événement')
            ->setEntityLabelInPlural('Événements')
            ->setSearchFields(['name'])
            // Utilise le nom exact de ton entité :
            ->setDefaultSort(['beginningHourEvent' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom de l\'événement');

        // Utilise les bons noms de champs :
        yield DateTimeField::new('beginningHourEvent', 'Date & Heure de début')
            ->setFormat('dd/MM/yyyy HH:mm')
            ->setFormTypeOptions([
                'html5' => true,
                'widget' => 'single_text',
            ]);

        yield DateTimeField::new('endHourEvent', 'Date & Heure de fin')
            ->setFormat('dd/MM/yyyy HH:mm')
            ->setFormTypeOptions([
                'html5' => true,
                'widget' => 'single_text',
            ]);

        yield AssociationField::new('groups', 'Groupes rattachés')
            ->setFormTypeOptions([
                'by_reference' => false,
                'multiple' => true,

                // C'est ici qu'on personnalise le texte dans la liste déroulante :
                'choice_label' => function ($group) {

                    $etablissement = $group->getEstablishment() ? $group->getEstablishment()->getName() : 'Sans établissement';


                    $niveau = $group->getName() ?: $group->getCode();


                    return sprintf('%s — %s', $etablissement, $niveau);
                }])
            ->setHelp('Sélectionnez les groupes à rattacher à cet événement.');
    }

    public function configureActions(Actions $actions): Actions
    {
        // Sécurité visuelle : On cache le bouton "Supprimer" sur l'index et le détail
        // si l'événement possède au moins un groupe rattaché.
        // D'ailleurs, j'ai du passer les groupes en nullable car sinon c'était impossible de supprimer un événement
        // Et que les groupes n'était pas nullables.. (RG-EVENT 01)  .
        $checkNoGroupsAttached = static function (Event $event) {
            return $event->getGroups()->isEmpty();
        };

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) use ($checkNoGroupsAttached) {
                return $action->displayIf($checkNoGroupsAttached);
            })
            ->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) use ($checkNoGroupsAttached) {
                return $action->displayIf($checkNoGroupsAttached);
            });
    }

    /**
     * Sécurité absolue (RG-EVENT-01) : Si l'utilisateur force l'URL de suppression en POST,
     * on intercepte la requête avant l'action de Doctrine pour bloquer la suppression en BDD.
     */
    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Event $entityInstance */
        if (!$entityInstance->getGroups()->isEmpty()) {
            $this->addFlash('danger', 'Action impossible : Des groupes sont encore rattachés à cet événement (RG-EVENT-01).');
            return;
        }

        parent::deleteEntity($entityManager, $entityInstance);
        $this->addFlash('success', 'L\'événement a été supprimé avec succès.');
    }
}

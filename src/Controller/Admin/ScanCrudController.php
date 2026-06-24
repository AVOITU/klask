<?php

namespace App\Controller\Admin;

use App\Entity\Scan;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class ScanCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Scan::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Scan')
            ->setEntityLabelInPlural('Historique des Scans')
            // On affiche les scans les plus récents en premier par défaut
            ->setDefaultSort(['hourValidation' => 'DESC'])
            // On affiche plus de résultats par page vu que c'est un journal de logs
            ->setPaginatorPageSize(50);
    }

    public function configureActions(Actions $actions): Actions
    {
        // 🔒 SÉCURITÉ ABSOLUE (RG-SCAN-01)
        // On désactive totalement la création, la modification et la suppression.
        // Personne, pas même l'admin, ne peut altérer cet historique.
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            // On autorise juste la consultation (la petite icône "œil")
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID Scan');

        yield DateTimeField::new('hourValidation', 'Horodatage')
            // On affiche jusqu'aux secondes pour une traçabilité précise
            ->setFormat('dd/MM/yyyy HH:mm:ss');

        // L'association systématique (Élève + Stand)
        yield AssociationField::new('user', 'Élève scanné');
        yield AssociationField::new('activity', 'Stand / Activité visitée');
    }
}

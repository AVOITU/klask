<?php

namespace App\Controller\Admin;

use App\Entity\AppParameter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AppParameterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string { return AppParameter::class; }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Paramètre Système')
            ->setEntityLabelInPlural('Configuration Générale')
            // Sécurité : On empêche la création ou suppression de clés système essentielles
            ->disable(Crud::PAGE_NEW, Crud::PAGE_DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('paramKey', 'Clé du paramètre')->createAsImmutable();
        yield TextField::new('paramValue', 'Valeur actuelle');
        yield TextField::new('description', 'Description / Rôle')->createAsImmutable();
    }
}

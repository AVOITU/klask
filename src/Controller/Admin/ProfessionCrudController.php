<?php

namespace App\Controller\Admin;

use App\Entity\Profession;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProfessionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Profession::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Profession')
            ->setEntityLabelInPlural('Professions / Métiers')
            ->setSearchFields(['name', 'codeRome'])
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom du métier / Profession');

        yield TextField::new('codeRome', 'Code ROME')
            ->setHelp('Code officiel France Travail (Ex: M1805)')
            ->setMaxLength(5);

        yield TextField::new('narrator', 'Nom du Conteur')
            ->setHelp('Prénom ou nom du professionnel. Affichera une pop-up de quête narrative lors du scan.');

        yield TextareaField::new('description', 'Description du métier')->hideOnIndex();

        // Permet de voir quelles activités/stands sont rattachés à ce métier (en lecture seule)
        yield AssociationField::new('activities', 'Stands associés')->onlyOnDetail();
    }
}

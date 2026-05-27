<?php

namespace App\Controller\Admin;

use App\Entity\Sphere;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SphereCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sphere::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Sphère')
            ->setEntityLabelInPlural('Sphères')
            ->setSearchFields(['name']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom');
        yield ColorField::new('color', 'Couleur');
        yield TextareaField::new('description', 'Description')->hideOnIndex();
        yield TextField::new('icon', 'Icône (CSS class)')->hideOnIndex();
        yield AssociationField::new('category', 'Catégorie')->hideOnIndex();
        yield AssociationField::new('activities', 'Activités')->hideOnIndex();
        yield NumberField::new('pointX', 'Centre X (%)')->setNumDecimals(2);
        yield NumberField::new('pointY', 'Centre Y (%)')->setNumDecimals(2);
        yield NumberField::new('radius', 'Rayon (%)')->setNumDecimals(2);
    }

    public function configureActions(Actions $actions): Actions
    {
        $place = Action::new('placeOnMap', 'Placer', 'fa fa-map-marker-alt')
            ->linkToRoute('admin_map_placement', fn (Sphere $s) => ['type' => 'sphere', 'id' => $s->getId()]);

        return $actions->add(Crud::PAGE_INDEX, $place)->add(Crud::PAGE_EDIT, $place);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Service\ActivityService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class ActivityCrudController extends AbstractCrudController
{
    public function __construct(private readonly ActivityService $activityService) {}

    public static function getEntityFqcn(): string
    {
        return Activity::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Activité')
            ->setEntityLabelInPlural('Activités / Stands')
            ->setDefaultSort(['name' => 'ASC'])
            ->setSearchFields(['name', 'description']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom');
        yield TextareaField::new('description', 'Description')->hideOnIndex();
        yield AssociationField::new('sphere', 'Sphère');
        yield AssociationField::new('category', 'Catégorie');
        yield BooleanField::new('isAvailable', 'Disponible');
        yield IntegerField::new('estimatedWaitMinutes', 'Attente (min)')->hideOnIndex();
        yield NumberField::new('pointX', 'Position X (%)')->hideOnIndex()->setNumDecimals(2);
        yield NumberField::new('pointY', 'Position Y (%)')->hideOnIndex()->setNumDecimals(2);
        yield IntegerField::new('softLimit', 'Limite souple')->hideOnIndex();
        yield IntegerField::new('hardLimit', 'Limite dure')->hideOnIndex();
        yield BooleanField::new('isInternship', 'Stage')->hideOnIndex();
        yield TextField::new('qrcode', 'QR Code')->hideOnIndex()->hideOnForm();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('isAvailable', 'Disponible'))
            ->add(EntityFilter::new('sphere', 'Sphère'))
            ->add(EntityFilter::new('category', 'Catégorie'));
    }

    public function configureActions(Actions $actions): Actions
    {
        $place = Action::new('placeOnMap', 'Placer', 'fa fa-map-marker-alt')
            ->linkToRoute('admin_map_placement', fn (Activity $a) => ['type' => 'activity', 'id' => $a->getId()]);

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $place)
            ->add(Crud::PAGE_EDIT, $place);
    }

    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        $this->activityService->initQrCode($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        $this->activityService->updateTimestamp($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }
}

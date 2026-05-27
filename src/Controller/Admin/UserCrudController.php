<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Security\RoleSecurity;
use App\Service\AuthorityService;
use App\Service\InscriptionService;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly InscriptionService $inscriptionService,
        private readonly AuthorityService $authorityService,
    ) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Élève')
            ->setEntityLabelInPlural('Élèves')
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['pseudo', 'groupCode']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('pseudo', 'Pseudo');
        yield TextField::new('groupCode', 'Code groupe');
        yield AssociationField::new('group', 'Groupe');
        yield AssociationField::new('authority', 'Rôle')->hideOnIndex();
        yield IntegerField::new('invalidScanCount', 'Scans invalides')->hideOnForm();
        yield DateTimeField::new('blockedUntil', 'Bloqué jusqu\'au')->hideOnIndex();
        yield DateTimeField::new('pokedAt', 'Poké à')->onlyOnDetail();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('group', 'Groupe'))
            ->add(EntityFilter::new('authority', 'Rôle'));
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->join('entity.authority', 'a')
            ->andWhere('a.authorityUser = :role')
            ->setParameter('role', RoleSecurity::STUDENT->value);
    }

    public function createEntity(string $entityFqcn): User
    {
        $user = new User();
        $user->setPseudo($this->inscriptionService->generateUniquePseudo());
        $user->setAuthority($this->authorityService->findByRole(RoleSecurity::STUDENT->value));

        return $user;
    }
}

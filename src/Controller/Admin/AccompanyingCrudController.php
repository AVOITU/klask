<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Security\RoleSecurity;
use App\Service\AuthorityService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccompanyingCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly AuthorityService $authorityService,
        private readonly UserPasswordHasherInterface $hasher,
    ) {}

    public static function getEntityFqcn(): string { return User::class; }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Accompagnateur')
            ->setEntityLabelInPlural('Accompagnateurs')
            ->setSearchFields(['pseudo', 'email']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('pseudo', 'Nom');
        yield TextField::new('email', 'Email');
        yield TextField::new('groupCode', 'Code groupe');
        yield AssociationField::new('group', 'Groupe')->hideOnIndex();
        if ($pageName === Crud::PAGE_NEW) {
            yield TextField::new('password', 'Mot de passe temporaire');
        }
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->join('entity.authority', 'a')
            ->andWhere('a.authorityUser = :role')
            ->setParameter('role', RoleSecurity::ACCOMPANYING->value);
    }

    public function createEntity(string $entityFqcn): User
    {
        $user = new User();
        $user->setAuthority($this->authorityService->findByRole(RoleSecurity::ACCOMPANYING->value));

        return $user;
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        /** @var User $entityInstance */
        $plain = $entityInstance->getPassword() ?: 'TempKlask2026!';
        $entityInstance->setPassword($this->hasher->hashPassword($entityInstance, $plain));
        parent::persistEntity($em, $entityInstance);
    }
}

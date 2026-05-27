<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// POINT ENTREE D'EAsY AdMIN
#[IsGranted('ROLE_ADMIN')]
#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(private readonly AdminUrlGenerator $urlGenerator) {}

    public function index(): Response
    {
        $g = $this->urlGenerator;

        return $this->render('admin/dashboard.html.twig', [
            'links' => [
                'activities'     => $g->setController(ActivityCrudController::class)->setAction(Action::INDEX)->generateUrl(),
                'spheres'        => $g->setController(SphereCrudController::class)->setAction(Action::INDEX)->generateUrl(),
                'categories'     => $g->setController(ActivityCategoryCrudController::class)->setAction(Action::INDEX)->generateUrl(),
                'groups'         => $g->setController(GroupCrudController::class)->setAction(Action::INDEX)->generateUrl(),
                'establishments' => $g->setController(EstablishmentCrudController::class)->setAction(Action::INDEX)->generateUrl(),
                'users'          => $g->setController(UserCrudController::class)->setAction(Action::INDEX)->generateUrl(),
                'accompanying'   => $g->setController(AccompanyingCrudController::class)->setAction(Action::INDEX)->generateUrl(),
            ],
        ]);
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('styles/admin.css');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Klask — Admin')
            ->setFaviconPath('favicon.ico')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Voir la carte', 'fa fa-map', 'app_map');

        yield MenuItem::section('Carte');
        yield MenuItem::linkTo(SphereCrudController::class, 'Sphères', 'fa fa-circle');
        yield MenuItem::linkTo(ActivityCrudController::class, 'Activités / Stands', 'fa fa-star');
        yield MenuItem::linkTo(ActivityCategoryCrudController::class, 'Catégories', 'fa fa-tag');

        yield MenuItem::section('Event');
        yield MenuItem::linkTo(GroupCrudController::class, 'Groupes', 'fa fa-users');
        yield MenuItem::linkTo(EstablishmentCrudController::class, 'Établissements', 'fa fa-school');

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkTo(UserCrudController::class, 'Élèves', 'fa fa-graduation-cap');
        yield MenuItem::linkTo(AccompanyingCrudController::class, 'Accompagnateurs', 'fa fa-user-tie');

        yield MenuItem::section('');
        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out');
    }
}

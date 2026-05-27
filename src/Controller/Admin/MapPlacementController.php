<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Entity\Sphere;
use App\Repository\ActivityCategoryRepository;
use App\Repository\ActivityRepository;
use App\Repository\SphereRepository;
use App\Service\ActivityService;
use App\Service\MapService;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class MapPlacementController extends AbstractController
{
    public function __construct(
        private readonly MapService $mapService,
        private readonly ActivityService $activityService,
        private readonly AdminUrlGenerator $urlGenerator,
        private readonly SphereRepository $sphereRepository,
        private readonly ActivityRepository $activityRepository,
        private readonly ActivityCategoryRepository $activityCategoryRepository,
    ) {}

    #[Route('/admin/placement/{type}/{id}', name: 'admin_map_placement', requirements: ['type' => 'sphere|activity'], methods: ['GET', 'POST'])]
    public function __invoke(Request $request, string $type, int $id): Response
    {
        $entity = match ($type) {
            'sphere'   => $this->sphereRepository->find($id),
            'activity' => $this->activityRepository->find($id),
        };

        if (!$entity instanceof Sphere && !$entity instanceof Activity) {
            throw $this->createNotFoundException();
        }

        if ($request->isMethod('POST')) {
            $this->mapService->savePosition(
                $entity,
                (float) $request->request->get('pointX', 50),
                (float) $request->request->get('pointY', 50),
                $entity instanceof Sphere ? (float) $request->request->get('radius', $entity->getRadius()) : null,
            );
            $this->addFlash('success', 'Position enregistrée.');

            return $this->redirectToRoute('admin_map_placement', compact('type', 'id'));
        }

        $crudController = $type === 'sphere' ? SphereCrudController::class : ActivityCrudController::class;
        $backUrl = $this->urlGenerator
            ->setController($crudController)
            ->setAction(Action::EDIT)
            ->setEntityId($id)
            ->generateUrl();

        return $this->render('admin/placement.html.twig', [
            'type'       => $type,
            'id'         => $id,
            'label'      => $entity->getName(),
            'pointX'     => $entity->getPointX() ?? 50,
            'pointY'     => $entity->getPointY() ?? 50,
            'radius'     => $entity instanceof Sphere ? $entity->getRadius() : null,
            'backUrl'    => $backUrl,
            'categories' => $type === 'sphere' ? $this->activityCategoryRepository->findAll() : [],
            'mapJson'    => json_encode($this->mapService->getPreparedSpheres()),
        ]);
    }

    #[Route('/admin/activity/new-on-map', name: 'admin_activity_new_on_map', methods: ['POST'])]
    public function newActivityOnMap(Request $request): Response
    {
        $sphereId = (int) $request->request->get('sphereId');
        $sphere   = $this->sphereRepository->find($sphereId);
        $category = $this->activityCategoryRepository->find((int) $request->request->get('categoryId'));

        if (!$sphere || !$category) {
            throw $this->createNotFoundException();
        }

        $name = trim((string) $request->request->get('name'));
        if ($name === '') {
            $this->addFlash('error', 'Le nom est obligatoire.');
            return $this->redirectToRoute('admin_map_placement', ['type' => 'sphere', 'id' => $sphereId]);
        }

        $activity = $this->activityService->createFromMap(
            $sphere,
            $category,
            $name,
            $request->request->get('description') ?: null,
            (float) $request->request->get('pointX', 50),
            (float) $request->request->get('pointY', 50),
        );

        $this->addFlash('success', sprintf('Activité "%s" créée.', $activity->getName()));

        return $this->redirectToRoute('admin_map_placement', ['type' => 'sphere', 'id' => $sphereId]);
    }
}

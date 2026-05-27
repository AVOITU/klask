<?php

namespace App\Service\Impl;

use App\Entity\Activity;
use App\Entity\Sphere;
use App\Repository\SphereRepository;
use App\Service\MapService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
class MapServiceImpl implements MapService
{
    public function __construct(
        private readonly SphereRepository $sphereRepository,
        private readonly EntityManagerInterface $em,
    ) {}

    public function getPreparedSpheres(): array
    {
        $spheres = $this->sphereRepository->findAllWithActivities();
        $result  = [];

        foreach ($spheres as $sphere) {
            $activityData = [];

            foreach ($sphere->getActivities() as $activity) {
                // les clés JSON 'pointXActivity' /'pointYActivity' / 'descriptionActivity' sont des alias legacy
                // hérités du code de devClement, entité utilise getPointX Y / getDescription
                // map.js et map.html.twig attendent ces noms : ne pas renommer sans tout mettre à jour
                $activityData[] = [
                    'name'                => $activity->getName(),
                    'pointXActivity'      => $activity->getPointX() ?? 50.0,
                    'pointYActivity'      => $activity->getPointY() ?? 50.0,
                    'descriptionActivity' => $activity->getDescription() ?? 'Aucune description',
                    'isAvailable'         => $activity->isAvailable(),
                    'isInternship'        => $activity->isInternship(),
                    'waitMinutes'         => $activity->getEstimatedWaitMinutes(),
                ];
            }

            // Priorité aux coordonnées explicites de la sphère (fixées en fixtures / admin)
            // calcul de la bounding-box des activités (code devClément conservé)
            if ($sphere->getPointX() !== null) {
                $centerX = $sphere->getPointX();
                $centerY = $sphere->getPointY() ?? 50.0;
                $size    = $sphere->getRadius();
            } elseif (count($activityData) > 0) {
                $xs = array_column($activityData, 'pointXActivity');
                $ys = array_column($activityData, 'pointYActivity');

                $centerX = (min($xs) + max($xs)) / 2;
                $centerY = (min($ys) + max($ys)) / 2;
                $diffX   = max($xs) - min($xs);
                $diffY   = (max($ys) - min($ys)) * (1600 / 2400);
                $size    = round(max($diffX, $diffY) + 8, 1);
            } else {
                $centerX = 50.0;
                $centerY = 50.0;
                $size    = 10.0;
            }

            $result[] = [
                'id'         => $sphere->getId(),
                'name'       => $sphere->getName(),
                'color'      => $sphere->getColor(),
                'centerX'    => $centerX,
                'centerY'    => $centerY,
                'size'       => $size,
                'activities' => $activityData,
            ];
        }

        return $result;
    }

    public function savePosition(Sphere|Activity $entity, float $x, float $y, ?float $radius = null): void
    {
        $entity->setPointX($x);
        $entity->setPointY($y);
        if ($entity instanceof Sphere && $radius !== null) {
            $entity->setRadius($radius);
        }
        $this->em->flush();
    }
}

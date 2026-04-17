<?php

namespace App\Service\Impl;

use App\Repository\SphereRepository;
use App\Service\MapService;

class MapServiceImpl implements MapService
{
    public function __construct(
        private readonly SphereRepository $sphereRepository
    ) {}

    public function getPreparedSpheres(): array
    {
        // 1. On récupère les vraies entités de la base de données
        $spheres = $this->sphereRepository->findAllWithActivities();
        $result = [];

        // 2. On boucle pour les transformer en données formatées pour la vue
        foreach ($spheres as $sphere) {
            $activities = $sphere->getActivities();
            $activityData = [];
            
            // Variables pour calculer la "Bounding Box" (Boîte englobante)
            $minX = 100; $maxX = 0; 
            $minY = 100; $maxY = 0;

            foreach ($activities as $activity) {
                // On récupère les coordonnées (avec sécurité si null)
                $x = $activity->getPointXActivity() ?? 50;
                $y = $activity->getPointYActivity() ?? 50;
                
                // Mise à jour des extrêmes
                $minX = min($minX, $x);
                $maxX = max($maxX, $x);
                $minY = min($minY, $y);
                $maxY = max($maxY, $y);

                $activityData[] = [
                    'pointXActivity' => $x,
                    'pointYActivity' => $y,
                    'descriptionActivity' => $activity->getDescriptionActivity() ?? 'Aucune description',
                ];
            }

            // 3. Calcul du centre et de la taille
            // 3. Calcul du centre et de la taille
            if (count($activities) > 0) {
                $centerX = ($minX + $maxX) / 2;
                $centerY = ($minY + $maxY) / 2;
                
                // On calcule l'écart en pourcentage.
                // Notre future carte fera 2400x1600 (ratio 3:2). 
                // Pour faire un rond parfait, on ajuste la différence Y au ratio de la carte.
                $diffX = $maxX - $minX;
                $diffY = ($maxY - $minY) * (1600 / 2400); 
                
                $maxDiff = max($diffX, $diffY);
                
                // La taille est maintenant un pourcentage (largeur de la carte) + 8% de marge
                $size = round($maxDiff + 8, 1); 
            } else {
                $centerX = 50;
                $centerY = 50;
                $size = 10; // 10% par défaut
            }

            // 4. On ajoute la sphère prête à l'emploi dans notre tableau final
            $result[] = [
                'id' => $sphere->getId(),
                'name' => $sphere->getNameSphere(),
                'color' => $sphere->getColorSphere(),
                'centerX' => $centerX,
                'centerY' => $centerY,
                'size' => $size,
                'activities' => $activityData,
            ];
        }

        return $result;
    }
}
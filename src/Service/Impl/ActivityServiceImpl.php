<?php

namespace App\Service\Impl;

use App\Entity\Activity;
use App\Entity\ActivityCategory;
use App\Entity\Sphere;
use App\Service\ActivityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\Uid\Uuid;

#[AsAlias]
class ActivityServiceImpl implements ActivityService
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function initQrCode(Activity $activity): void
    {
        if ($activity->getQrcodeToken() !== null) {
            return;
        }

        $token = Uuid::v4()->toRfc4122();
        $activity->setQrcodeToken($token);
        $activity->setQrcode($token);
    }

    public function updateTimestamp(Activity $activity): void
    {
        $activity->setStandUpdatedAt(new \DateTimeImmutable());
    }

    public function createFromMap(Sphere $sphere, ActivityCategory $category, string $name, ?string $description, float $x, float $y): Activity
    {
        $activity = (new Activity())
            ->setName($name)
            ->setDescription($description)
            ->setPointX($x)
            ->setPointY($y)
            ->setSphere($sphere)
            ->setCategory($category);

        $this->initQrCode($activity);
        $this->em->persist($activity);
        $this->em->flush();

        return $activity;
    }
}

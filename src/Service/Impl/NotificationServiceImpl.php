<?php

namespace App\Service\Impl;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use App\Service\NotificationService;


class NotificationServiceImpl implements NotificationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private HubInterface $hub
    ) {}

    public function sendAutomaticAlert(string $title, string $message, string $colorCode): void
    {
        // 1. Sauvegarde en Base de données (L'historique)
        $notification = new Notification();
        $notification->setTitle($title);
        $notification->setMessage($message);
        $notification->setColorCode($colorCode);
        $notification->setIsActive(true);
        // Le createdAt est géré automatiquement

        $this->em->persist($notification);
        $this->em->flush();

        // 2. Pousse le Pop-up en temps réel (Mercure)
        $data = json_encode([
            'title' => $title,
            'message' => $message,
            'colorCode' => $colorCode
        ]);

        $update = new Update('klask/notifications', $data);
        $this->hub->publish($update);
    }
}

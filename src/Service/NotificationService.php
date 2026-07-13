<?php

namespace App\Service;

use App\Entity\Notification;


interface NotificationService
{
    /**
     * Envoie une alerte automatique en base de données et via Mercure (Pop-up)
     */
    public function sendAutomaticAlert(string $title, string $message, string $colorCode): void;
}

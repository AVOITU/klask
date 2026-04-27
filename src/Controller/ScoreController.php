<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

interface ScoreController
{
    public function score(): Response;
}
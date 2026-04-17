<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MapControllerTest extends WebTestCase
{
    public function testMapPageIsUp(): void
    {
        $client = static::createClient();
        $client->request('GET', '/map');

        // On vérifie que la page répond bien (Code HTTP 200)
        $this->assertResponseIsSuccessful();
    }
}
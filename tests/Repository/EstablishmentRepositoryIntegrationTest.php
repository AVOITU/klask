<?php

// tests/Repository/EstablishmentRepositoryIntegrationTest.php
namespace App\Tests\Repository;

use App\Repository\EstablishmentRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EstablishmentRepositoryIntegrationTest extends KernelTestCase
{
    public function testFindDistinctEstablishments(): void
    {
        self::bootKernel();
        $repository = self::getContainer()->get(EstablishmentRepository::class);

        $result = $repository->findDistinctEstablishments();
        $this->assertIsArray($result);
    }
}

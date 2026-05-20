<?php

namespace App\Repository;

use App\Entity\AppParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppParameter>
 */
class AppParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppParameter::class);
    }

    // valeur castée d'un paramètre par clé - throw si clé absente en bdd
    /** @throws \RuntimeException */
    public function getValue(string $key): int|float|bool|string
    {
        $parameter = $this->findOneBy(['paramKey' => $key]);

        if ($parameter === null) {
            throw new \RuntimeException(sprintf('AppParameter "%s" introuvable en base.', $key));
        }

        return $parameter->getCastedValue();
    }
}

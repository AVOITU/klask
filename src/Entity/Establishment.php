<?php

namespace App\Entity;

use App\Repository\EstablishmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstablishmentRepository::class)]
class Establishment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name_establishment = null;

    public function __construct()
    {
    }

    public function getNameEstablishment(): ?string
    {
        return $this->name_establishment;
    }

    public function setNameEstablishment(string $name_establishment): static
    {
        $this->name_establishment = $name_establishment;

        return $this;
    }

}

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

    #[ORM\Column]
    private ?int $id_establishment = null;

    #[ORM\Column(length: 100)]
    private ?string $name_establishment = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdEstablishment(): ?int
    {
        return $this->id_establishment;
    }

    public function setIdEstablishment(int $id_establishment): static
    {
        $this->id_establishment = $id_establishment;

        return $this;
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

<?php

namespace App\Entity;

use App\Repository\Impl\ClassroomRepositoryImpl;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassroomRepositoryImpl::class)]
class Classroom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idClassroom = null;

    #[ORM\Column(length: 100)]
    private ?string $nameClassroom = null;

    public function getIdClassroom(): ?int
    {
        return $this->idClassroom;
    }

    public function setIdClassroom(int $idClassroom): static
    {
        $this->idClassroom = $idClassroom;

        return $this;
    }

    public function getNameClassroom(): ?string
    {
        return $this->nameClassroom;
    }

    public function setNameClassroom(string $nameClassroom): static
    {
        $this->nameClassroom = $nameClassroom;

        return $this;
    }

}

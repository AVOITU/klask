<?php

namespace App\Entity;

use App\Repository\Impl\UserRepositoryImpl;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepositoryImpl::class)]
final class Student
{
    #[ORM\Column]
    private int $idUser;
    #[ORM\Column]
    private string $pseudoUser;
    #[ORM\Column]
    private Classroom $classRoom;
    #[ORM\Column]
    private Authority $authority;

    /** @var Validation[] */
    #[ORM\Column]
    private array $validations;

    /**
     * @param Validation[] $validations
     * @param int $idUser
     * @param string $pseudoUser
     * @param Classroom $classRoom
     * @param Authority $authority
     */
    public function __construct(array $validations, Authority $authority,
                                int   $idUser, string $pseudoUser, Classroom $classRoom)
    {
        $this->validations = $validations;
        $this->authority = $authority;
        $this->idUser = $idUser;
        $this->pseudoUser = $pseudoUser;
        $this->classRoom = $classRoom;
    }

    public function getAuthority(): Authority
    {
        return $this->authority;
    }

    public function setAuthority(Authority $authority): void
    {
        $this->authority = $authority;
    }

    public function getValidations(): array
    {
        return $this->validations;
    }

    public function setValidations(array $validations): void
    {
        $this->validations = $validations;
    }

    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): void
    {
        $this->idUser = $idUser;
    }

    public function getPseudoUser(): string
    {
        return $this->pseudoUser;
    }

    public function setPseudoUser(string $pseudoUser): void
    {
        $this->pseudoUser = $pseudoUser;
    }

    public function getClassRoom(): Classroom
    {
        return $this->classRoom;
    }

    public function setClassRoom(Classroom $classRoom): void
    {
        $this->classRoom = $classRoom;
    }
}

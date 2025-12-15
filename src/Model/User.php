<?php

namespace Model;

final class User
{
    /** @var Validation[] */
    private array $validations;

    /** @var Authority[] */
    private array $authorities;

    private int $idUser;
    private string $pseudoUser;
    private ClassRoom $classRoom;

    /**
     * @param Validation[] $validations
     * @param Authority[] $authorities
     * @param int $idUser
     * @param string $pseudoUser
     * @param ClassRoom $classe
     */
    public function __construct(array $validations, array $authorities, int $idUser, string $pseudoUser, ClassRoom $classe)
    {
        $this->validations = $validations;
        $this->authorities = $authorities;
        $this->idUser = $idUser;
        $this->pseudoUser = $pseudoUser;
        $this->classRoom = $classe;
    }

    public function getValidations(): array
    {
        return $this->validations;
    }

    public function setValidations(array $validations): void
    {
        $this->validations = $validations;
    }

    public function getAuthorities(): array
    {
        return $this->authorities;
    }

    public function setAuthorities(array $authorities): void
    {
        $this->authorities = $authorities;
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

    public function getClassRoom(): ClassRoom
    {
        return $this->classRoom;
    }

    public function setClassRoom(ClassRoom $classRoom): void
    {
        $this->classRoom = $classRoom;
    }
}
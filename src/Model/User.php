<?php

namespace Model;

final class User
{
    /** @var Validation[] */
    private array $validations;

    private int $idUser;
    private string $pseudoUser;
    private ClassRoom $classRoom;
    private Authority $authority;

    /**
     * @param Validation[] $validations
     * @param int $idUser
     * @param string $pseudoUser
     * @param ClassRoom $classRoom
     * @param Authority $authority
     */
    public function __construct(array $validations, Authority $authority,
                                int $idUser, string $pseudoUser, ClassRoom $classRoom)
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

    public function getClassRoom(): ClassRoom
    {
        return $this->classRoom;
    }

    public function setClassRoom(ClassRoom $classRoom): void
    {
        $this->classRoom = $classRoom;
    }
}
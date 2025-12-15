<?php

namespace Model;

class ClassRoom
{
    /** @var User[] */
    private array $users;

    private int $idClass;
    private string $school;
    private string $nameClass;

    /**
     * @param User[] $users
     * @param int $idClass
     * @param string $school
     * @param string $nameClass
     */
    public function __construct(array $users, int $idClass, string $school, string $nameClass)
    {
        $this->users = $users;
        $this->idClass = $idClass;
        $this->school = $school;
        $this->nameClass = $nameClass;
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function setUsers(array $users): void
    {
        $this->users = $users;
    }

    public function getIdClass(): int
    {
        return $this->idClass;
    }

    public function setIdClass(int $idClass): void
    {
        $this->idClass = $idClass;
    }

    public function getSchool(): string
    {
        return $this->school;
    }

    public function setSchool(string $school): void
    {
        $this->school = $school;
    }

    public function getNameClass(): string
    {
        return $this->nameClass;
    }

    public function setNameClass(string $nameClass): void
    {
        $this->nameClass = $nameClass;
    }

}
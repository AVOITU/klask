<?php

namespace Model;

class ClassRoom
{
    /** @var User[] */
    private array $users;

    private int $idClass;
    private string $school;
    private string $className;

    /**
     * @param User[] $users
     * @param int $idClass
     * @param string $school
     * @param string $className
     */
    public function __construct(array $users, int $idClass, string $school, string $className)
    {
        $this->users = $users;
        $this->idClass = $idClass;
        $this->school = $school;
        $this->className = $className;
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

    public function getClassName(): string
    {
        return $this->className;
    }

    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

}
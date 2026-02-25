<?php

namespace App\Entity;

use App\Repository\Impl\ClassroomRepositoryImpl;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassroomRepositoryImpl::class)]
class Classroom
{
    /** @var User[] */
    #[ORM\Column]
    private array $users;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $idClass;
    #[ORM\Column]
    private string $school;
    #[ORM\Column]
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

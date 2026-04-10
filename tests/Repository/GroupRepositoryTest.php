<?php
namespace App\Tests\Repository;

use App\Entity\Establishment;
use App\Entity\Group;
use App\Entity\Event;
use App\Repository\Impl\GroupRepositoryImpl;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class GroupRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?GroupRepositoryImpl $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->repository = self::getContainer()->get(GroupRepositoryImpl::class);
    }

    public function testQbByEstablishmentReturnsCorrectGroups(): void
    {
        $event = new Event();
        $event->setNameEvent('Test Event');
        $this->entityManager->persist($event); 

        $ecoleA = new Establishment();
        $ecoleA->setNameEstablishment('Collège Brizeux');
        $this->entityManager->persist($ecoleA);

        $ecoleB = new Establishment();
        $ecoleB->setNameEstablishment('Lycée Chaptal');
        $this->entityManager->persist($ecoleB);

        $group1 = new Group();
        $group1->setNameGroup('3ème A');
        $group1->setEstablishment($ecoleA);
        $group1->setEvent($event);
        $this->entityManager->persist($group1);

        $group2 = new Group();
        $group2->setNameGroup('2nde Générale');
        $group2->setEstablishment($ecoleB);
        $group2->setEvent($event);
        $this->entityManager->persist($group2);

        $this->entityManager->flush();

        $queryBuilder = $this->repository->qbByEstablishment('Collège Brizeux');
        $results = $queryBuilder->getQuery()->getResult();

        $this->assertCount(1, $results);
        $this->assertEquals('3ème A', $results[0]->getNameGroup());
    }

    public function testQbByEstablishmentReturnsNothingWhenNull(): void
    {
        $ecole = new Establishment();
        $ecole->setNameEstablishment('Test');
        $this->entityManager->persist($ecole);
        $this->entityManager->flush();

        $results = $this->repository->qbByEstablishment(null)->getQuery()->getResult();

        $this->assertCount(0, $results, "La requête ne devrait rien renvoyer si l'établissement est null.");
    }

    public function testFindById(): void
    {
        $event = new Event() ;
        $event->setNameEvent("Test Event");
        $this->entityManager->persist($event); // AJOUT

        $est = new Establishment();
        $est->setNameEstablishment('Lycée Chaptal');
        $this->entityManager->persist($est); // AJOUT

        $group = new Group();
        $group->setNameGroup('Terminalé S');
        $group->setEstablishment($est);
        $group->setEvent($event);
        $this->entityManager->persist($group);

        $this->entityManager->flush();

        $id = $group->getId();
        $foundGroup = $this->repository->findById($id);
        $this->assertNotNull($foundGroup);
        $this->assertEquals('Terminalé S', $foundGroup->getNameGroup());
    }

    public function testqbByEstablishmentOrder(): void
    {
        $event = new Event();
        $event->setNameEvent('Test Event');
        $this->entityManager->persist($event);

        $est = new Establishment();
        $est->setNameEstablishment('Lycée Chaptal');
        $this->entityManager->persist($est);

        $groupA = new Group();
        $groupA->setNameGroup('Terminale S');
        $groupA->setEstablishment($est);
        $groupA->setEvent($event);
        $this->entityManager->persist($groupA);

        $groupB = new Group();
        $groupB->setNameGroup('2nde Générale');
        $groupB->setEstablishment($est);
        $groupB->setEvent($event);
        $this->entityManager->persist($groupB);

        $this->entityManager->flush();

        $results = $this->repository->qbByEstablishment('Lycée Chaptal')->getQuery()->getResult();

        $this->assertCount(2, $results);
        $this->assertEquals('2nde Générale', $results[0]->getNameGroup());
    }

    public function testQbByEstablishmentWithNoGroups(): void
    {
        $est = new Establishment();
        $est->setNameEstablishment('Lycée Sans Groupes');
        $this->entityManager->persist($est);
        $this->entityManager->flush();

        $results = $this->repository->qbByEstablishment('Lycée Sans Groupes')->getQuery()->getResult();

        $this->assertCount(0, $results, "La requête devrait renvoyer une collection vide si aucun groupe n'est associé à l'établissement.");
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
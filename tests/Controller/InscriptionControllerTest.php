<?php

namespace App\Tests\Controller;

use App\Entity\Establishment;
use App\Entity\Event;
use App\Entity\Group;
use App\Entity\User;
use App\Security\RoleSecurity;
use App\Entity\Authority;
use App\Entity\Role;
use App\Entity\AuthorityRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InscriptionControllerTest extends WebTestCase
{
    private ?EntityManagerInterface $entityManager;
    private KernelBrowser $client;

    /**
     * Cette méthode s'exécute avant chaque test.
     * Elle prépare le client (navigateur) et l'EntityManager.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // 1. On crée le client en PREMIER. 
        // Cela démarre le Kernel de Symfony de manière propre.
        $this->client = static::createClient();
        
        // 2. On récupère l'EntityManager depuis le container qui est maintenant booté.
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
    }

    /**
     * Vérifie que la page d'inscription s'affiche correctement.
     */
    public function testInscriptionPageIsUp(): void
    {
        $this->client->request('GET', '/inscription');
        
        $this->assertResponseIsSuccessful();
    }

    /**
     * Vérifie que le changement d'établissement met à jour le formulaire.
     */
    public function testEstablishmentChange(): void
    {
        // ARRANGE : On prépare une école en base
        $establishment = new Establishment();
        $establishment->setNameEstablishment('Lycée Yves Thépot - Quimper');
        $this->entityManager->persist($establishment);
        $this->entityManager->flush();

        // ACT : On simule la sélection de cette école
        $this->client->request('POST', '/inscription/establishment-change', [
            'inscription_form' => [
                'establishment' => 'Lycée Yves Thépot - Quimper',
                'pseudoUser' => 'TestUser',
            ]
        ]);

        // ASSERT
        $this->assertResponseIsSuccessful();
        $crawler = $this->client->getCrawler();
        
        // On vérifie que le pseudo est conservé
        $actualValue = $crawler->filter('input[name="inscription_form[pseudoUser]"]')->attr('value');
        $this->assertEquals('TestUser', $actualValue);

        // On vérifie que l'école est bien marquée comme sélectionnée
        $selectedOption = $crawler->filter('select[name="inscription_form[establishment]"] option[selected="selected"]');
        $this->assertEquals('Lycée Yves Thépot - Quimper', $selectedOption->attr('value'));
    }

    /**
     * Teste un parcours complet d'inscription réussi.
     */
    public function testFullInscriptionFlow(): void
    {
        // --- ARRANGE ---
        $role = new Role();
        $role->setNameRole(RoleSecurity::STUDENT->value);
        $this->entityManager->persist($role);
        $this->entityManager->flush();


        // CRÉATION DE L'AUTORITÉ (Requis par le service)
        $roleSecurity = RoleSecurity::STUDENT->value;
        $authority = new Authority();
        $authority->setAuthorityUser($roleSecurity);
        $this->entityManager->persist($authority);
        $this->entityManager->flush();
        
        $authorityRole = new AuthorityRole();
        $authorityRole->setAuthority($authority);
        $authorityRole->setRole($role);
        $this->entityManager->persist($authorityRole);

        // On sauvegarde tout le décor
        $this->entityManager->flush();


        $event = new Event();
        $event->setNameEvent('Event Test');
        $this->entityManager->persist($event);

        $establishment = new Establishment();
        $establishment->setNameEstablishment('Lycée Yves Thépot - Quimper');
        $this->entityManager->persist($establishment);

        $group = new Group();
        $group->setNameGroup('BTS 1');
        $group->setEstablishment($establishment);
        $group->setEvent($event);
        $this->entityManager->persist($group);
        $this->entityManager->flush();

        

        $groupId = $group->getId();

        // --- ACT ---
        $crawler = $this->client->request('POST', '/inscription/establishment-change', [
            'inscription_form' => [
                'establishment' => 'Lycée Yves Thépot - Quimper',
                'pseudoUser' => 'TestUserUnique'
            ]
        ]);

        $form = $crawler->selectButton('Valider l\'inscription')->form();

        $form['inscription_form[establishment]'] = 'Lycée Yves Thépot - Quimper';
        $form['inscription_form[group]'] = $groupId; 
        $form['inscription_form[pseudoUser]'] = 'TestUserUnique';

        $this->client->submit($form);

        // --- ASSERT ---
        // On vérifie qu'on est bien redirigé (succès)
        $this->assertResponseRedirects('/inscription');
    }
    public function testSaveInscriptionWithDuplicatePseudo(): void
    {
        // --- ARRANGE ---
        $event = new Event();
        $event->setNameEvent('Event Test');
        $this->entityManager->persist($event);

        $establishment = new Establishment();
        $establishment->setNameEstablishment('Lycée Yves Thépot - Quimper');
        $this->entityManager->persist($establishment);

        $group = new Group();
        $group->setNameGroup('BTS 1');
        $group->setEstablishment($establishment);
        $group->setEvent($event);
        $this->entityManager->persist($group);

        $authority = new Authority();
        $authority->setAuthorityUser('STUDENT');
        $this->entityManager->persist($authority);

        // On crée l'utilisateur déjà présent
        $existingUser = new User();
        $existingUser->setPseudoUser('Antoine');
        $existingUser->setGroup($group);
        $existingUser->setAuthority($authority);
        $this->entityManager->persist($existingUser);

        $this->entityManager->flush();

        // --- ACT ---
        // CORRECTION : On passe par establishment-change pour charger les <option> du select
        $crawler = $this->client->request('POST', '/inscription/establishment-change', [
            'inscription_form' => [
                'establishment' => 'Lycée Yves Thépot - Quimper',
                'pseudoUser' => 'Antoine'
            ]
        ]);

        $form = $crawler->selectButton('Valider l\'inscription')->form();

        $form['inscription_form[establishment]'] = 'Lycée Yves Thépot - Quimper';
        $form['inscription_form[group]'] = $group->getId(); 
        $form['inscription_form[pseudoUser]'] = 'Antoine';

        $this->client->submit($form);

        // --- ASSERT ---
        $this->assertResponseIsSuccessful(); 
        $this->assertSelectorTextContains('body', 'Erreur lors de la création.');
    }

    /**
     * On nettoie l'EntityManager après chaque test pour éviter les fuites de mémoire.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        if ($this->entityManager) {
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }
}
<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

class InscriptionControllerTest extends WebTestCase
{
    public function testInscriptionPageIsUp(): void
    {
        // 1. On crée un client (navigateur simulé)
        $client = static::createClient();

        // 2. On demande une URL de ton projet (ex: la page d'inscription)
        // Remplace '/inscription' par une de tes vraies routes
        $client->request('GET', '/inscription');

        // 3. On vérifie que le serveur répond un code 200 (Succès)
        $this->assertResponseIsSuccessful();
    }
    public function testInscriptionPageIsDown(): void
    {
        $client = static::createClient();

        // On demande une URL qui n'existe pas pour simuler une page "down"
        $client->request('GET', '/inscription/nonexistent');

        // On vérifie que le serveur répond un code 404 (Not Found)
        $this->assertResponseStatusCodeSame(404);
    }

    public function testSchoolChange(): void
    {
        $client = static::createClient();

        // Simuler une requête POST pour changer d'école
        $client->request('POST', '/inscription/school-change', [
        'inscription_form' => [ // La clé parente qui englobe tout
            'school' => 'Lycée Yves Thépot - Quimper',
            'pseudoUser' => 'TestUser',
            ]
        ]);

        // Vérifier que la réponse est un succès
        $this->assertResponseIsSuccessful();

        // Optionnel : Vérifier que le formulaire a été mis à jour avec les nouvelles données
        $crawler = $client->getCrawler();
        $pseudoInput = $client->getCrawler()->filter('input[name="inscription_form[pseudoUser]"]');

        // On vérifie d'abord que le champ existe
        $this->assertCount(1, $pseudoInput, "Le champ pseudo n'a pas été trouvé dans la page.");

        // On récupère la valeur réelle de l'attribut value
        $actualValue = $client->getCrawler()->filter('input[name="inscription_form[pseudoUser]"]')->attr('value');
        $this->assertNotEmpty($actualValue, "Le pseudo ne devrait pas être vide.");

        // On récupère le sélecteur d'école
        $schoolSelect = $crawler->filter('select[name="inscription_form[school]"]');

        // On vérifie que l'option envoyée ("Lycée Yves Thépot - Quimper") est bien celle sélectionnée
        // Dans un <select>, l'option choisie a l'attribut "selected"
        $selectedOption = $schoolSelect->filter('option[selected="selected"]');

        $this->assertCount(1, $selectedOption, "Aucune école n'est marquée comme sélectionnée.");
        $this->assertEquals('Lycée Yves Thépot - Quimper', $selectedOption->attr('value'), "L'école sélectionnée n'est pas la bonne.");
        
    }

    public function testRegeneratePseudo(): void
    {
        $client = static::createClient();

        // Simuler une requête POST pour régénérer un pseudo
        $client->request('POST', '/inscription/regen', [
            'inscription_form' => [
            'school' => 'Lycée Yves Thépot - Quimper',
            'pseudoUser' => 'TestUser']
        ]);

        // Vérifier que la réponse est un succès
        $this->assertResponseIsSuccessful();

        // Optionnel : Vérifier que le pseudo a été mis à jour avec une nouvelle valeur
        $crawler = $client->getCrawler();
        $pseudoInput = $crawler->filter('input[name="inscription_form[pseudoUser]"]');

        // On vérifie d'abord que le champ existe
        $this->assertCount(1, $pseudoInput, "Le champ pseudo n'a pas été trouvé dans la page.");

        // On récupère la valeur réelle de l'attribut value
        $actualValue = $pseudoInput->attr('value');
        $this->assertNotEmpty($actualValue, "Le pseudo ne devrait pas être vide.");
        $this->assertNotEquals('TestUser', $actualValue, "Le pseudo n'a pas été régénéré.");
    }

    public function testFullInscriptionFlow(): void
    {
        $client = static::createClient();

        // 1. On choisit d'abord l'école pour "charger" les classes
        $crawler = $client->request('POST', '/inscription/school-change', [
            'inscription_form' => [
                'school' => 'Lycée Yves Thépot - Quimper',
                'pseudoUser' => 'TestUser'
            ]
        ]);

        // 2. Maintenant que le $crawler contient la page AVEC les classes,
        // on récupère le formulaire de cette nouvelle page
        $form = $crawler->selectButton('Valider l\'inscription')->form();

        // 3. On remplit la classe (qui est maintenant disponible)
        // Vérifie le nom exact de l'option dans ton HTML pour 'group'
        $form['inscription_form[group]'] = '18'; 
        $form['inscription_form[pseudoUser]'] = 'TestUserUnique';

        // 4. On soumet au point d'entrée final
        $client->submit($form);

        // 5. Là, ça devrait rediriger !
        $this->assertResponseRedirects('/inscription');
    }

    public function testSaveInscriptionWithDuplicatePseudo(): void
    {
        $client = static::createClient();

        // 1. On créer un utilisateur avec un pseudo spécifique
        $crawler = $client->request('POST', '/inscription/school-change', [
            'inscription_form' => [
                'school' => 'Lycée Yves Thépot - Quimper',
                'pseudoUser' => 'Antoine'
            ]
        ]);

        $form = $crawler->selectButton('Valider l\'inscription')->form();

        $form['inscription_form[group]'] = '18'; 
        $form['inscription_form[pseudoUser]'] = 'Antoine';

        $client->submit($form);   
        $this->assertResponseRedirects('/inscription');

        // 2. On vide la redirection pour revenir à la page d'inscription
        $client->followRedirect();

        // 3. On tente de créer un autre utilisateur avec le même pseudo
        $crawler = $client->request('POST', '/inscription/school-change', [
            'inscription_form' => [
                'school' => 'Lycée Yves Thépot - Quimper',
                'pseudoUser' => 'Antoine'
            ]
        ]);

        $form = $crawler->selectButton('Valider l\'inscription')->form();

        $form['inscription_form[group]'] = '18'; 
        $form['inscription_form[pseudoUser]'] = 'Antoine';

        $client->submit($form);

        // 3. VÉRIFICATIONS
        // On ne doit PAS être redirigé vers /show, on reste sur la page (code 200)
        $this->assertResponseRedirects('/inscription');
        $crawler = $client->followRedirect();

        // 1. On affiche le contenu pour débugger (à enlever après)
        // echo $client->getResponse()->getContent();

        // 2. On vérifie si l'erreur est dans un message Flash
        $flashExists = $crawler->filter('.alert-error')->count() > 0;

        // 3. On vérifie si l'erreur est directement liée au champ du formulaire
        $formErrorExists = $crawler->filter('li:contains("Le pseudonyme est déjà pris")')->count() > 0;

        $this->assertTrue($flashExists || $formErrorExists, "L'erreur de pseudo n'apparaît ni en Flash, ni dans le formulaire.");
    
    }
}
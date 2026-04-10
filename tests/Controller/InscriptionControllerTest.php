<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

class InscriptionControllerTest extends WebTestCase
{
    public function testInscriptionPageIsUp(): void
    {
        $client = static::createClient();

        $client->request('GET', '/inscription');

        $this->assertResponseIsSuccessful();
    }
    public function testInscriptionPageIsDown(): void
    {
        $client = static::createClient();
        $client->request('GET', '/inscription/nonexistent');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testSchoolChange(): void
    {
        $client = static::createClient();

        $client->request('POST', '/inscription/school-change', [
        'inscription_form' => [
            'school' => 'Lycée Yves Thépot - Quimper',
            'pseudoUser' => 'TestUser',
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $crawler = $client->getCrawler();
        $pseudoInput = $client->getCrawler()->filter('input[name="inscription_form[pseudoUser]"]');

        $this->assertCount(1, $pseudoInput, "Le champ pseudo n'a pas été trouvé dans la page.");

        $actualValue = $client->getCrawler()->filter('input[name="inscription_form[pseudoUser]"]')->attr('value');
        $this->assertNotEmpty($actualValue, "Le pseudo ne devrait pas être vide.");

        $schoolSelect = $crawler->filter('select[name="inscription_form[school]"]');

        $selectedOption = $schoolSelect->filter('option[selected="selected"]');

        $this->assertCount(1, $selectedOption, "Aucune école n'est marquée comme sélectionnée.");
        $this->assertEquals('Lycée Yves Thépot - Quimper', $selectedOption->attr('value'), "L'école sélectionnée n'est pas la bonne.");
        
    }

    public function testRegeneratePseudo(): void
    {
        $client = static::createClient();

        $client->request('POST', '/inscription/regen', [
            'inscription_form' => [
            'school' => 'Lycée Yves Thépot - Quimper',
            'pseudoUser' => 'TestUser']
        ]);

        $this->assertResponseIsSuccessful();

        $crawler = $client->getCrawler();
        $pseudoInput = $crawler->filter('input[name="inscription_form[pseudoUser]"]');

        $this->assertCount(1, $pseudoInput, "Le champ pseudo n'a pas été trouvé dans la page.");

        $actualValue = $pseudoInput->attr('value');
        $this->assertNotEmpty($actualValue, "Le pseudo ne devrait pas être vide.");
        $this->assertNotEquals('TestUser', $actualValue, "Le pseudo n'a pas été régénéré.");
    }

    public function testFullInscriptionFlow(): void
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/inscription/school-change', [
            'inscription_form' => [
                'school' => 'Lycée Yves Thépot - Quimper',
                'pseudoUser' => 'TestUser'
            ]
        ]);

        $form = $crawler->selectButton('Valider l\'inscription')->form();

        $form['inscription_form[group]'] = '18'; 
        $form['inscription_form[pseudoUser]'] = 'TestUserUnique';

        $client->submit($form);
        $this->assertResponseRedirects('/inscription');
    }

    public function testSaveInscriptionWithDuplicatePseudo(): void
    {
        $client = static::createClient();
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

        $client->followRedirect();

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

        $this->assertResponseIsSuccessful(); 
        $this->assertSelectorTextContains('form', 'Le pseudonyme est déjà pris.');
    }
}
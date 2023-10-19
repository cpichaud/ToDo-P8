<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private $client;


    protected function setUp(): void
    {
        $this->client = static::createClient([], ['debug' => true]);
    }
 
        public function testDisplayLoginPage()
    {
        $crawler = $this->client->request('GET', '/login');
        
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.login-link', 'connexion utilisateur');

    }

    public function testLoginWithInvalidCredentials()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form();

        // Soumettre des informations d'identification incorrectes
        $this->client->submit($form, ['_username' => 'wrong@example.com', '_password' => 'wrongpassword']);

        // Vérifier que la réponse est une redirection vers la page de connexion
        $this->assertResponseRedirects('http://localhost/login');


        // Suivre la redirection pour vérifier le message d'erreur
        $crawler = $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-danger', 'Identifiants invalides.');
    }

    public function testSuccessfulLogin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form();

        // Soumettre des informations d'identification valides
        $this->client->submit($form, ['_username' => 'admin@example.com', '_password' => 'adminpassword']);
        
        // Vérifier la redirection après une connexion réussie
        $this->assertResponseRedirects('http://localhost/');
    }

}


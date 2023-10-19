<?php


namespace App\Tests\Controller;

use App\Controller\SecurityController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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

    public function testLoginReturnsCorrectErrorMessage()
    {
        // Faites une requête GET à la page de login.
        $crawler = $this->client->request('GET', '/login');

        // Soumettez le formulaire de connexion avec des informations d'identification incorrectes.
        $form = $crawler->selectButton('Connexion')->form();
        $this->client->submit($form, ['_username' => 'nonexistent@example.com', '_password' => 'wrongpassword']);
        
        // Suivez la redirection (après une tentative de connexion infructueuse, vous devriez être redirigé vers la page de login à nouveau).
        $crawler = $this->client->followRedirect();

        // Vérifiez que le message d'erreur est présent dans la réponse.
        $this->assertStringContainsString('Identifiants invalides.', $this->client->getResponse()->getContent());
    }




}


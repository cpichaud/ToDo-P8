<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

use App\Repository\UserRepository;

class UserControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient([], ['debug' => true]);
    }
    
    public function testListActionForAdmin()
    {
        // Récupération de l'utilisateur avec le rôle ROLE_ADMIN
        $userRepository = static::$container->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@example.com');
    
        // Authentification de l'utilisateur
        $this->client->loginUser($adminUser);
    
        // Essai d'accès à la route /users
        $this->client->request('GET', '/users');
    
        // Assertion pour vérifier que l'utilisateur a accès
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
    
    public function loginUser(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form();
        $this->client->submit($form, ['_username' => 'admin@example.com', '_password' => 'adminpassword']);
    }

    public function testCreateAction()
    {
        $this->loginUser();

        $crawler = $this->client->request('GET', '/users/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Créer')->form();
        $form['user[password][first]'] = 'testttt1234';
        $form['user[password][second]'] = 'testttt1234';
        $form['user[email]'] = 'autre@gmail.com';
        $form['user[roles][0]']->tick();
        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testeditAction()
    {
        $this->loginUser();

        $crawler = $this->client->request('GET', '/users/1/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[password][first]'] = 'edit';
        $form['user[password][second]'] = 'edit';
        $form['user[email]'] = 'edit@gmail.com';
        $form['user[roles][0]']->tick();
        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

}

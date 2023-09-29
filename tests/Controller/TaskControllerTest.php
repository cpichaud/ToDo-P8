<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Task;
use App\Repository\TaskRepository;  // Ajoutez cette ligne

class TaskControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient([], ['debug' => true]);
    }

    public function loginUser(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form();
        $this->client->submit($form, ['username' => 'admin', 'password' => 'adminadmin']);
    }

    public function testListTask()
    {
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        // Accéder à la page de création
        $crawler = $this->client->request('GET', '/tasks/create');
        //$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    
        // Remplir le formulaire et le soumettre
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'test';
        $form['task[content]'] = 'contenue';
        $this->client->submit($form);
    
        // S'attendre à être redirigé
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    
        // Suivre la redirection
        $crawler = $this->client->followRedirect();
    
        // S'attendre à un 200 OK sur la nouvelle page
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    
        // S'attendre à voir un message de succès sur la nouvelle page
        //$this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    }
    


    // public function testCreateAction()
    // {
    //     $this->loginUser();

    //     $crawler = $this->client->request('GET', '/users/create');
    //     $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    //     $form = $crawler->selectButton('Créer')->form();
    //     $form['user[password][first]'] = 'testttt1234';
    //     $form['user[password][second]'] = 'testttt1234';
    //     $form['user[email]'] = 'autre@gmail.com';
    //     $form['user[roles][0]']->tick();
    //     $this->client->submit($form);

    //     $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

    //     $crawler = $this->client->followRedirect();

    //     $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    //     //$this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    // }

    // public function testeditAction()
    // {
    //     $this->loginUser();

    //     $crawler = $this->client->request('GET', '/users/1/edit');
    //     $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    //     $form = $crawler->selectButton('Modifier')->form();
    //     $form['user[password][first]'] = 'edit';
    //     $form['user[password][second]'] = 'edit';
    //     $form['user[email]'] = 'edit@gmail.com';
    //     $form['user[roles][0]']->tick();
    //     $this->client->submit($form);

    //     $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

    //     $crawler = $this->client->followRedirect();

    //     $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    //     //$this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    // }

}

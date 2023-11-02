<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class TaskControllerTest extends WebTestCase
{
    private $client;
    private $createdTaskId;

    protected function setUp(): void
    {
        $this->client = static::createClient([], ['debug' => true]);
    }

    /**
     * Log in a user.
     *
     * @param string $username
     * @param string $password
     */
    public function loginUser($username = 'admin@example.com', $password = 'adminpassword'): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form();
        $this->client->submit($form, ['_username' => $username, '_password' => $password]);
    }

    public function testListTask()
    {
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->loginUser();

        $crawler = $this->client->request('GET', '/tasks/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'test';
        $form['task[content]'] = 'contenu';
        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteTask()
    {
        $this->loginUser();

        // Supprimez la tâche
        $this->client->request('GET', '/tasks/84/delete');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode()); // S'attendre à une redirection après la suppression

        // Suivez la redirection et vérifiez que la tâche a été supprimée
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEditTask()
    {
        $taskIdToEdit = 37;

        // Simulez que $testUser est connecté
        $this->loginUser('edit@gmail.com', 'edit');
        $crawler = $this->client->request('GET', '/tasks/' . $taskIdToEdit . '/edit');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
        $this->loginUser();
        $testAdmin = $this->loginUser('admin@example.com', 'adminpassword');
        
        // Accédez à la page d'édition de la tâche (ajustez l'URL si nécessaire)
        $crawler = $this->client->request('GET', '/tasks/' . $taskIdToEdit . '/edit');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Remplissez le formulaire avec les nouvelles données
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Titre modifié';
        $form['task[content]'] = 'Contenu modifié';

        // Soumettez le formulaire
        $this->client->submit($form);

        // Vérifiez la redirection vers la page d'accueil
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}

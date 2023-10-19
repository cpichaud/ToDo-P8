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

    public function loginUser($username = 'admin@example.com', $password = 'adminpassword'): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form();
        $this->client->submit($form, ['_username' => $username, '_password' => $password]);
    }
    public function logoutUser(): void
    {
        $this->client->request('GET', '/logout'); 
    }    
    
    public function testListTask()
    {
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        // 1. Authentification
        $this->loginUser();
        
        // 2. Accéder à la page de création
        $crawler = $this->client->request('GET', '/tasks/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        
        // 3. Remplir le formulaire et le soumettre
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'test';
        $form['task[content]'] = 'contenue';
        $this->client->submit($form);  // Notez qu'il n'y a pas de champ 'task[user]' ici

        // 4. Vérifications
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteTask()
    {
        $this->loginUser();  // Authentifiez-vous en tant qu'admin ou l'utilisateur qui a créé la tâche

        // Supprimez la tâche
        $this->client->request('GET', '/tasks/82/delete');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());  // S'attendre à une redirection après la suppression

        // Suivez la redirection et vérifiez que la tâche a été supprimée
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
       // $this->assertStringNotContainsString('test', $this->client->getResponse()->getContent());
    }

    public function testEditTask()
    {
        $taskIdToEdit = 37;

        // Simulez que $testUser est connecté
        $this->loginUser('edit@gmail.com', 'edit');
        $crawler = $this->client->request('GET', '/tasks/' . $taskIdToEdit . '/edit');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    
        // Maintenant, connectez-vous en tant qu'administrateur ou l'utilisateur qui a créé la tâche et essayez de modifier la tâche à nouveau
        $this->loginUser();
        
    
        // Récupérez l'utilisateur de test
        // $testUser = $userRepository->findOneByEmail('admin@admin.com');  // Ajustez l'e-mail pour correspondre à un utilisateur dans votre base de données
    
        // // Simulez que $testUser est connecté
        // $this->client->loginUser($testUser);
    
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
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());  // La soumission devrait vous rediriger
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());  // S'attendre à un 200 OK sur la nouvelle page
    }  
}

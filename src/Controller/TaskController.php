<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class TaskController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="task_list")
     */
    public function listAction(): Response
    {
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();
        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setIsDone(false);
            $user = $this->getUser();
    
            if ($user instanceof UserInterface) {
                $task->setUser($user);
            } else {
                $userAnonyme = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'anonyme@example.com']);
                $task->setUser($userAnonyme);
            }
    
            $task->setCreateAt(new \DateTime());
            $this->entityManager->persist($task);
            $this->entityManager->flush();
    
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');
    
            return $this->redirectToRoute('task_list');
        }
        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(int $id, Request $request): Response
    {
        $task = $this->entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Tâche non trouvée');
        }

        // Get the currently logged-in user
        $user = $this->getUser();

        // Check if the logged-in user is the author of the task or an administrator
        if ($user !== $task->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier cette tâche');
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(int $id): Response
    {
        $task = $this->entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Tâche non trouvée');
        }

        $task->toggle(!$task->isDone());
        $this->entityManager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(int $id): Response
    {
        try {
            $currentUser = $this->getUser();
            if (!$currentUser) {
                throw new AccessDeniedException('Vous devez être connecté pour accéder à cette fonctionnalité.');
            }

            $task = $this->entityManager->getRepository(Task::class)->find($id);

            if (!$task) {
                throw $this->createNotFoundException('Tâche non trouvée');
            }

            if ($currentUser !== $task->getUser() && !$currentUser->hasRole('ROLE_ADMIN')) {
                $this->addFlash('danger', 'Vous ne pouvez pas supprimer cette tâche.');
                return $this->redirectToRoute('task_list');
            }

            $this->entityManager->remove($task);
            $this->entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');

            return $this->redirectToRoute('task_list');
        } catch (AuthenticationException $e) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder à cette fonctionnalité.');
            return $this->redirectToRoute('login_route_name');
        }
    }
}

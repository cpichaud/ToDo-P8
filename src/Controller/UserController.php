<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/users", name="user_list")
     * @IsGranted("ROLE_ADMIN")
     */
    public function listAction(): Response
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        return $this->render('user/list.html.twig', ['users' => $users]);
    }

    // /**
    //  * @Route("/users/create", name="user_create")
    //  */
    // public function createAction(Request $request): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
    //         $user->setPassword($password);
    //         // dump($user->getRoles());
    //         // die();

    //         $this->entityManager->persist($user);
    //         $this->entityManager->flush();

    //         $this->addFlash('success', "L'utilisateur a bien été ajouté.");

    //         return $this->redirectToRoute('user_list');
    //     }

    //     return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    // }

   /**
 * @Route("/users/{id}/edit", name="user_edit")
 */
public function editAction(Request $request, int $id): Response
{
    $user = $this->getDoctrine()->getRepository(User::class)->find($id);

    if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $this->addFlash('success', "L'utilisateur a bien été modifié");

        return $this->redirectToRoute('user_list');
    }

    return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
}

}

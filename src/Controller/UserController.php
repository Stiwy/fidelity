<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class UserController extends AbstractController
{
    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        return $this->renderForm('pages/user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'form' => $form
        ]);
    }

    #[Route('/nouveau', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (is_null($form->get('password')->getData())) {
                return $this->redirectToRoute('user_index', [Response::HTTP_SEE_OTHER]);
            }

            $roles = [$form->get('roles')->getData()];
            $password = $hasher->hashPassword($user, $form->get('password')->getData());

            $user->setRoles($roles);
            $user->setPassword($password);

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [Response::HTTP_SEE_OTHER]);
    }

    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->json($user);
    }

    #[Route('/editer/{id}', name: 'user_edit', methods: ['POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!is_null($form->get('password')->getData())) {
                $password = $hasher->hashPassword($user, $form->get('password')->getData());
                $user->setPassword($password);
            }

            if (!is_null($form->get('roles')->getData())) {
                $roles = [$form->get('roles')->getData()];
                $user->setRoles($roles);
            }

            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete', $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
}

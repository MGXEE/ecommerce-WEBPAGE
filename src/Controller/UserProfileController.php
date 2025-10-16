<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\Cart;

final class UserProfileController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;
    private CsrfTokenManagerInterface $csrfTokenManager;
    private RequestStack $requestStack;
    private TokenStorageInterface $tokenStorage;

    // Konstruktor zum Injizieren von PasswordHasher, CSRF-Manager, RequestStack & TokenStorage
    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        CsrfTokenManagerInterface $csrfTokenManager,
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
    }

    #[Route('/user/profile', name: 'app_user_profile')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserProfileType::class, $user, [
            'is_admin' => $this->isGranted('ROLE_ADMIN'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Your profile was updated.');
            return $this->redirectToRoute('app_user_profile');
        }

        $users = [];
        if ($this->isGranted('ROLE_ADMIN')) {
            $users = $em->getRepository(User::class)->findAll();
        }

        // CSRF Token für Löschformular generieren
        $deleteToken = $this->csrfTokenManager->getToken('delete-profile')->getValue();

        return $this->render('user_profile/index.html.twig', [
            'form' => $form->createView(),
            'users' => $users,
            'delete_token' => $deleteToken,
        ]);
    }

    #[Route('/admin/users', name: 'admin_user_list')]
    public function listUsers(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // nur Admins dürfen

        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/user_list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/user/{id}', name: 'admin_edit_user', requirements: ['id' => '\d+'])]
    public function editUser(User $user, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // Admin-Schutz

        $form = $this->createForm(UserProfileType::class, $user, [
            'is_admin' => true,  // Admin darf Rollen ändern
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $hashed = $this->passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashed);
            }

            $em->flush();

            $this->addFlash('success', 'User updated!');
            return $this->redirectToRoute('admin_user_list'); // zurück zur Userliste
        }

        return $this->render('admin/user_edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/admin/user/new', name: 'admin_user_new')]
    public function createUser(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new User();
        $form = $this->createForm(UserProfileType::class, $user, [
            'is_admin' => true,  // Admin darf Rollen setzen
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $hashed = $this->passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashed);
            }

            if (empty($user->getRoles())) {
                $user->setRoles(['ROLE_USER']);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Neuer Benutzer wurde erstellt.');
            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/profile/delete', name: 'app_user_profile_delete', methods: ['POST'])]
    public function deleteProfile(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to delete your account.');
        }

        if (!$this->isCsrfTokenValid('delete-profile', $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        // Zuerst alle Cart-Entitäten dieses Users löschen, um FK-Constraint Fehler zu vermeiden
        $carts = $em->getRepository(Cart::class)->findBy(['user' => $user]);
        foreach ($carts as $cart) {
            $em->remove($cart);
        }

        $em->remove($user);
        $em->flush();

        // Session invalidieren
        $session = $this->requestStack->getSession();
        if ($session !== null) {
            $session->invalidate();
        }

        // User-Token aus Security-TokenStorage entfernen (abmelden)
        $this->tokenStorage->setToken(null);

        return $this->redirectToRoute('app_static');
    }
}

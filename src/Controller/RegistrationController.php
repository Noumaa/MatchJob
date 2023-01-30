<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User\BusinessFormType;
use App\Form\User\PersonFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * Gère l'inscription d'une entreprise pour le site web.
     * 
     * @param Request $request Requête HTTP contenant les données soumises du formulaire
     * @param UserPasswordHasherInterface $userPasswordHasher Utilisé pour hasher le mot de passe de l'utilisateur
     * @param EntityManagerInterface $entityManager Gère les opérations de persistance pour les entités de l'application
     * 
     * @return Response La réponse HTTP qui peut être une redirection vers la page d'accueil ou une vue qui affiche le formulaire d'inscription
     */
    #[Route('/pro/inscription', name: 'app_register_business')]
    public function registerBusiness(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(BusinessFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->register($user, $form, $userPasswordHasher, $entityManager);

            $user->addRole("ROLE_BUSINESS");
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'type' => "business",
        ]);
    }

    /**
     * Gère l'inscription d'un particulier pour le site web.
     * 
     * @param Request $request Requête HTTP contenant les données soumises du formulaire
     * @param UserPasswordHasherInterface $userPasswordHasher Utilisé pour hasher le mot de passe de l'utilisateur
     * @param EntityManagerInterface $entityManager Gère les opérations de persistance pour les entités de l'application
     * 
     * @return Response La réponse HTTP qui peut être une redirection vers la page d'accueil ou une vue qui affiche le formulaire d'inscription
     */
    #[Route('/inscription', name: 'app_register')]
    public function registerPerson(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(PersonFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->register($user, $form, $userPasswordHasher, $entityManager);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'type' => "person",
        ]);
    }

    /**
     * Enregistre un nouvel utilisateur en hasheant son mot de passe et en lui envoyant un email de confirmation.
     * 
     * @param User $user L'utilisateur à enregistrer
     * @param Form $form Le formulaire soumis contenant les données de l'utilisateur
     * @param UserPasswordHasherInterface $hasher L'interface utilisée pour hasher le mot de passe de l'utilisateur
     * @param EntityManagerInterface $manager Gère les opérations de persistance pour les entités de l'application
     */
    private function register(User $user, Form $form, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager) {
        // encode the plain password
        $user->setPassword(
            $hasher->hashPassword(
                $user,
                $form->get('user')->get('plainPassword')->getData()
            )
        );

        $manager->persist($user);
        $manager->flush();

        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('noreply@matchjob.app', 'MatchJob'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }

    /**
     * Vérifie la validité d'un lien de confirmation d'email pour un utilisateur.
     *
     * @param Request $request L'instance de requête
     * @param TranslatorInterface $translator L'interface de traduction
     * @param UserRepository $userRepository Le repository d'utilisateurs
     *
     * @return Response L'instance de réponse
     *
     * @throws VerifyEmailExceptionInterface Si la vérification de l'email échoue
     */
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // TODO: Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}

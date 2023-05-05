<?php

namespace App\Controller\Authentication;

use App\Entity\User;
use App\Form\Authentication\BusinessFormType;
use App\Form\Authentication\PersonFormType;
use App\Form\Authentication\UserType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/pro/inscription', name: 'app_register_business')]
    public function registerBusiness(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createForm(BusinessFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->register($user, $form, $userPasswordHasher, $entityManager);

            $user->setRoles(["ROLE_BUSINESS"]);
            $entityManager->flush();

            $this->addFlash('success', 'Vous y êtes presque ! Un email de confirmation vous a été envoyé');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register_business.html.twig', [
            'proRegistrationForm' => $form->createView(),
        ]);
    }

    #[Route('/inscription', name: 'app_register')]
    public function registerPerson(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createForm(PersonFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->register($user, $form, $userPasswordHasher, $entityManager);

            $user->setRoles(["ROLE_PERSON"]);
            $entityManager->flush();

            $this->addFlash('success', 'Vous y êtes presque ! Un email de confirmation vous a été envoyé');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register_person.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Enregistre un nouvel utilisateur en hasheant son mot de passe et en lui envoyant un email de confirmation.
     *
     * @param User $user L'utilisateur à enregistrer
     * @param FormInterface $form Le formulaire soumis contenant les données de l'utilisateur
     * @param UserPasswordHasherInterface $hasher L'interface utilisée pour hasher le mot de passe de l'utilisateur
     * @param EntityManagerInterface $manager Gère les opérations de persistance pour les entités de l'application
     */
    public function register(User $user, FormInterface $form, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager)
    {
        $user->setPassword(
            $hasher->hashPassword(
                $user,
                $form->get('user')->get('plainPassword')->getData()
            )
        );
        // TODO remove after completing LocationType
        $user->setCountry("France");
        $manager->persist($user);
        $manager->flush();

        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('noreply@matchjob.xyz', 'MatchJob'))
                ->to($user->getEmail())
                ->subject('Confirmation de votre adresse email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }

    #[Route('/confirmation', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
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
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Adresse email confirmée avec succès !');

        return $this->redirectToRoute('app_home');
    }
}

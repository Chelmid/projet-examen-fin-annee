<?php

namespace App\Controller\connexion;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Service\CategoryService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;
    private $category;

    public function __construct(EmailVerifier $emailVerifier, CategoryService $categoryService)
    {
        $this->emailVerifier = $emailVerifier;
        $this->category =  $categoryService->getFullCategories();
    }

    /**
     * @Route("/{_locale}/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {

            $user = new User();
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                if ($form->get('email')->getData() == 'lo.chelmi@gmail.com') {
                    $user->setRoles(['ROLE_ADMIN']);
                } else {
                    $user->setRoles(['ROLE_USER']);
                }
                $user->setIsVerified(0);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('lo.chelmi@gmail.com', 'moi'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('client/registration/confirmation_email.html.twig')
                );
                // do anything else you need here, like send an email
                $this->addFlash('successRegistre', 'Vous etes bien inscrit, Notre équipe reviendra vers vous pour la confirmation de votre compte');
                return $this->redirectToRoute('homeClient');
            }

            return $this->render('client/registration/register.html.twig', [
                'registrationForm' => $form->createView(),
                'categories' => $this->category
            ]);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
            ]);
        }
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}

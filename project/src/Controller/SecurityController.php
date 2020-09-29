<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class SecurityController extends AbstractController
{
    private $twig;
    private $category;

    public function __construct( Environment $twig,CategoryRepository $categoryRepository)
    {
        $this->twig = $twig;
        $this->category = $categoryRepository->findAll();
    }
    /**
     * @Route("/{_locale}/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, CategoryRepository $categoryRepository, Request $request): Response
    {
        if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
            $category = $categoryRepository->findAll();
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'categories' => $category]);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
            ]);
        }
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

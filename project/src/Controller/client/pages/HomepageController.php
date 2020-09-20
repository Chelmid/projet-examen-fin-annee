<?php

namespace App\Controller\client\pages;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomepageController extends AbstractController
{
    private $twig;


    public function __construct( Environment $twig)
    {
        $this->twig = $twig;
    }

    //vue home
    public function homeClient( TranslatorInterface $translator, Request $request,AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        /*dump($request->getLocale());
        die();*/
        return new Response($this->twig->render('base.html.twig', ['last_username' => $lastUsername, 'error' => $error]), 200);
    }

    //vue home
    public function loginClient( TranslatorInterface $translator, Request $request)
    {
        return new Response($this->twig->render('client/pages/cartClient.html.twig'), 200);
    }
    //vue home
    public function dashboardClient( TranslatorInterface $translator, Request $request)
    {
        return new Response($this->twig->render('client/pages/dashboardClient.html.twig'), 200);
    }

    //langue
    /*public function changeLocaleClient($locale,$url, Request $request)
    {
        // On stocke la langue dans la session
        $request->getSession()->set('_locale', $locale);

        // On revient sur la page précédente

        return $this->redirect($request->headers->get('referer'));
        //return $this->redirectToRoute('home', ['langue' => $request->getSession()->get('_locale')]);
    }*/

    public function contactClient( TranslatorInterface $translator, Request $request)
    {
        return new Response($this->twig->render('client/pages/contactClient.html.twig'), 200);
    }

    public function categorieClient( TranslatorInterface $translator, Request $request,$categorie)
    {
        return new Response($this->twig->render('client/pages/categorieClient.html.twig'), 200);
    }

    public function cartClient( TranslatorInterface $translator, Request $request)
    {
        return new Response($this->twig->render('client/pages/cartClient.html.twig'), 200);
    }

    public function searchClient( TranslatorInterface $translator, Request $request)
    {
        return new Response($this->twig->render('client/pages/searchClient.html.twig'), 200);
    }


}

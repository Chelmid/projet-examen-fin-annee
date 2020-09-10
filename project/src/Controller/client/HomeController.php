<?php

namespace App\Controller\client;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $twig;


    public function __construct( Environment $twig)
    {
        $this->twig = $twig;
    }

    //vue home
    public function homeClient( TranslatorInterface $translator, Request $request)
    {
        dump($request->getLocale());

        return new Response($this->twig->render('base.html.twig'), 200);
    }

    //vue home
    public function loginClient( TranslatorInterface $translator, Request $request)
    {
        return new Response($this->twig->render('pages/cartClient.html.twig'), 200);
    }
    //vue home
    public function dashboardClient( TranslatorInterface $translator, Request $request)
    {
        return new Response($this->twig->render('pages/dashboardClient.html.twig'), 200);
    }

    //langue
    public function changeLocaleClient($locale,$url, Request $request)
    {
        // On stocke la langue dans la session
        $request->getSession()->set('_locale', $locale);

        // On revient sur la page précédente

        return $this->redirect($request->headers->get('referer'));
        //return $this->redirectToRoute('home', ['langue' => $request->getSession()->get('_locale')]);
    }

    public function categorieClient( TranslatorInterface $translator, Request $request,$categorie)
    {
        return new Response($this->twig->render('pages/categorieClient.html.twig'), 200);
    }

    public function cartClient( TranslatorInterface $translator, Request $request)
    {
        return new Response($this->twig->render('pages/cartClient.html.twig'), 200);
    }

    public function searchClient( TranslatorInterface $translator, Request $request)
    {
        return new Response($this->twig->render('pages/searchClient.html.twig'), 200);
    }


}

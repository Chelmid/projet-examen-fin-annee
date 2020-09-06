<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HomeController extends AbstractController
{
    private $twig;

    public function __construct( Environment $twig)
    {
        $this->twig = $twig;
    }

    public function home( TranslatorInterface $translator, Request $request)
    {
        dump($request->getLocale());
        return new Response($this->twig->render('pages/french/home_FR.html.twig'));
    }

    public function changeLocale($locale, Request $request)
    {
        // On stocke la langue dans la session
        $request->getSession()->set('_locale', $locale);

        // On revient sur la page précédente
        //return $this->redirect($request->headers->get('referer'));
        return $this->redirectToRoute('home', ['langue' =>  $request->getSession()->get('_locale')]);
    }

}

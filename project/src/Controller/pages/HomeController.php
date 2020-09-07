<?php

namespace App\Controller\pages;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\auth\Login;

class HomeController extends AbstractController
{
    private $twig;


    public function __construct( Environment $twig)
    {
        $this->twig = $twig;
    }

    //vue home
    public function home( TranslatorInterface $translator, Request $request)
    {
        dump($request->getLocale());

        return new Response($this->twig->render('base.html.twig'), 200);
    }

    //vue home
    public function homeConnect( TranslatorInterface $translator, Request $request)
    {
        return new Response($this->twig->render('pages/homeConnect.html.twig'), 200);
    }
    //vue home
    public function dashboard( TranslatorInterface $translator, Request $request)
    {
        return new Response($this->twig->render('pages/dashboardUser.html.twig'), 200);
    }

    //langue
    public function changeLocale($locale,$url, Request $request)
    {
        // On stocke la langue dans la session
        $request->getSession()->set('_locale', $locale);

        // On revient sur la page précédente

        return $this->redirect($request->headers->get('referer'));
        //return $this->redirectToRoute('home', ['langue' => $request->getSession()->get('_locale')]);
    }


}

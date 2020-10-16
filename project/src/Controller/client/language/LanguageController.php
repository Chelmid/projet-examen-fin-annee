<?php

namespace App\Controller\client\language;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class LanguageController extends AbstractController
{
    //langue
    public function changeLocaleClient($locale, Request $request)
    {
        // On stocke la langue dans la session
        $request->getSession()->set('_locale', $locale);
        $url = $request->getSession();
        // On revient sur la page précédente
        return $this->redirect($request->headers->get('referer'));
        //return $this->redirectToRoute('homeClient', ['langue' => $request->getSession()->get('_locale')]);
    }
}

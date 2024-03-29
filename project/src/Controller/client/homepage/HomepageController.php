<?php

namespace App\Controller\client\homepage;

use App\Service\CategoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\Admin\AdminCategoryController;

class HomepageController extends AbstractController
{

    private $categories;

    public function __construct(CategoryService $categoryService)
    {
        $this->categories = $categoryService->getFullCategories();
    }

    //vue home
    public function homeClient(TranslatorInterface $translator, Request $request, AuthenticationUtils $authenticationUtils)
    {
        if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            //affichage category
            return $this->render('base.html.twig', [
                'last_username' => $lastUsername, 'error' => $error, 'categories' => $this->categories
            ]);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->categories
            ]);
        }
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

    public function contactClient(TranslatorInterface $translator, Request $request)
    {
        if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {
            return$this->render('client/autres/contactClient.html.twig', [
                'categories' => $this->categories
            ]);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->categories
            ]);
        }
    }

    public function searchClient(TranslatorInterface $translator, Request $request)
    {
        if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {
            return $this->render('client/autres/searchClient.html.twig', [
                'categories' => $this->categories
            ]);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->categories
            ]);
        }
    }
}

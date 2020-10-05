<?php

namespace App\Controller\client\pages;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\admin\AdminCategoryController;

class HomepageController extends AbstractController
{
    private $twig;
    private $category;


    public function __construct(Environment $twig, CategoryRepository $categoryRepository)
    {
        $this->twig = $twig;
        $this->category = $categoryRepository->findAll();
    }

    //vue home
    public function homeClient(TranslatorInterface $translator, Request $request, AuthenticationUtils $authenticationUtils)
    {
        if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();


            /*dump($request->getLocale());
            die();*/
            //affichage category
            return new Response($this->twig->render('base.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'categories' => $this->category]), 200);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
            ]);
        }
    }

    //vue home
    public function loginClient(TranslatorInterface $translator, Request $request)
    {
        if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {
            return new Response($this->twig->render('client/pages/cartClient.html.twig', ['categories' => $this->category]), 200);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
            ]);
        }
    }

    //vue home
    public function dashboardClient(TranslatorInterface $translator, Request $request)
    {
        if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {
            return new Response($this->twig->render('client/pages/dashboardClient.html.twig', ['categories' => $this->category]), 200);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
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
            return new Response($this->twig->render('client/pages/contactClient.html.twig', ['categories' => $this->category]), 200);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
            ]);
        }
    }

    /*public function categorieClient( TranslatorInterface $translator, Request $request,$categorie)
    {
        return new Response($this->twig->render('client/pages/category/categoryClient.html.twig', ['category'=> $this->category]), 200);
    }*/

    public function cartClient(TranslatorInterface $translator, Request $request)
    {
        if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {
            return new Response($this->twig->render('client/pages/cartClient.html.twig', ['categories' => $this->category]), 200);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
            ]);
        }
    }

    public function searchClient(TranslatorInterface $translator, Request $request)
    {
        if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {
            return new Response($this->twig->render('client/pages/searchClient.html.twig', ['categories' => $this->category]), 200);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
            ]);
        }
    }
}

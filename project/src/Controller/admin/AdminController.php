<?php

namespace App\Controller\admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private $twig;


    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    //vue home
    public function adminLogin(TranslatorInterface $translator, Request $request)
    {

        return new Response($this->twig->render('adminbase.html.twig'), 200);
    }
}
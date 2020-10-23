<?php

namespace App\Controller\Admin;

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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return new Response($this->twig->render('admin/adminbase.html.twig'), 200);
    }
}

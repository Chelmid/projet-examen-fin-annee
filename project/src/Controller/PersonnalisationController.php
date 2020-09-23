<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class PersonnalisationController extends AbstractController
{
    private $twig;
    private $category;


    public function __construct( Environment $twig,CategoryRepository $categoryRepository)
    {
        $this->twig = $twig;
        $this->category = $categoryRepository->findAll();
    }


    public function personnalisationClient($category, $product)
    {

        return $this->render('personnalisation/personnalisationClient.html.twig', [
            'controller_name' => 'PersonnalisationController',
            'categories'=> $this->category,
            'theCategory' => $category,
            'theProduct' => $product
        ]);
    }
}

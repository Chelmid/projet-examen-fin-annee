<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ProductController extends AbstractController
{

    private $twig;
    private $category;


    public function __construct( Environment $twig,CategoryRepository $categoryRepository)
    {
        $this->twig = $twig;
        $this->category = $categoryRepository->findAll();
    }


    public function ProductClient($category, $product)
    {

        return $this->render('product/product.html.twig', [
            'controller_name' => 'ProductController',
            'categories'=> $this->category,
            'theCategory' => $category,
            'theProduct' => $product
        ]);
    }
}

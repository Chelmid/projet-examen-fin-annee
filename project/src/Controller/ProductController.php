<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


    public function ProductClient($category, Product $product,Request $request)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['name' => $category]);
        if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es' && $category) {

        return $this->render('product/product.html.twig', [
            'controller_name' => 'ProductController',
            'categories'=> $this->category,
            'theCategory' => $category,
            'theProduct' => $product
        ]);}else{
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
            ]);
        }
    }
}

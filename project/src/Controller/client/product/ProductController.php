<?php

namespace App\Controller\client\product;

use App\Entity\Category;
use App\Entity\Product;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    private $category;

    public function __construct(CategoryService $categoryService)
    {
        $this->category = $categoryService->getFullCategories();
    }


    public function ProductClient($category, $product,$id, Request $request,$color)

    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['name' => $category]);
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['name' => $product]);

        if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es' && $category) {

                return $this->render('client/product/product.html.twig', [
                    'controller_name' => 'ProductController',
                    'categories' => $this->category,
                    'theCategory' => $category,
                    'theProduct' => $product,
                    'colorNow' => $color
                ]);

        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
            ]);
        }
    }
}

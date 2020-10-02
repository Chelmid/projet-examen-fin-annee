<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class PersonnalisationController extends AbstractController
{
    private $twig;
    private $category;


    public function __construct(Environment $twig, CategoryRepository $categoryRepository)
    {
        $this->twig = $twig;
        $this->category = $categoryRepository->findAll();
    }


    public function personnalisationClient($category, $product)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['name' => $product]);
        if ($product) {
            return $this->render('personnalisation/personnalisationClient.html.twig', [
                'controller_name' => 'PersonnalisationController',
                'categories' => $this->category,
                'theCategory' => $category,
                'theProduct' => $product
            ]);
        }else{
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
            ]);
        }
    }

    public function personnalisationCheckInfo(Request $request)
    {

        $i = 0;
        foreach ($request->request->all() as $value) {
            if (!empty($value && $value > 49)) {
                $i++;
            }
        }
        dump($i);
        if ($i <= 0) {
            $this->addFlash('errorQuantity', "il n'y pas de quantity");
            return $this->redirectToRoute('productClient', [
                'category' => $request->attributes->get('category'),
                'product' => $request->attributes->get('product'),
                'id' => $request->attributes->get('id'),
                'color' => $request->attributes->get('color'),
            ]);
        } else {
            return $this->redirectToRoute('personnalisationClient', [
                'category' => $request->attributes->get('category'),
                'product' => $request->attributes->get('product'),
                'id' => $request->attributes->get('id'),
                'color' => $request->attributes->get('color'),
            ]);
        }
    }
}

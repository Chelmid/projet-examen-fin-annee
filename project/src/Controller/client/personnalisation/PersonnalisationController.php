<?php

namespace App\Controller\client\personnalisation;

use App\Entity\Product;
use App\Entity\ZoneDeMarquage;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PersonnalisationController extends AbstractController
{
    private $category;

    public function __construct(CategoryService $categoryService)
    {
        $this->category = $categoryService->getFullCategories();
    }

    public function personnalisationClient($category, $product, Request $request, $save,$idImage)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['name' => $product]);
        $zoneDeMarquage = $this->getDoctrine()->getRepository(ZoneDeMarquage::class)->findOneBy(['product' => $request->attributes->get('id')]);
        if ($product) {
            return $this->render('personnalisation/personnalisationClient.html.twig', [
                'controller_name' => 'PersonnalisationController',
                'categories' => $this->category,
                'theCategory' => $category,
                'theProduct' => $product,
                'zoneDeMarquage' => $zoneDeMarquage,
                'idImage' => $idImage
            ]);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
            ]);
        }
    }

    public function personnalisationCheckInfo(Request $request)
    {
        $i = 0;
        $j = 0;
        $save = [];
        $idImage = '';
        foreach ($request->request->all() as $value) {
            if ($value < 50 && !empty($value)) {
                $this->addFlash('errorQuantity', "La quantité doit être égale à 50 ou plus");
                return $this->redirectToRoute('productClient', [
                    'category' => $request->attributes->get('category'),
                    'product' => $request->attributes->get('product'),
                    'id' => $request->attributes->get('id'),
                    'color' => $request->attributes->get('color'),
                ]);
            } else {
                $i++;
            }
        }
        if ($i >= 1) {

            foreach ($request->request->all() as $key => $value) {
                if ($value != 0 || !empty($value)) {
                    array_push($save, [$j => [$key => $value]]);
                    $j++;
                } else {
                    $j++;
                }
            }
            foreach ( $save[0] as $key => $value){
                $idImage = $key;
            }
        }
        return $this->personnalisationClient($request->attributes->get('category'), $request->attributes->get('product'), $request, $save,$idImage);
    }
}

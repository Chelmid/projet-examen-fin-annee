<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ZoneDeMarquage;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use App\Repository\ZoneDeMarquageRepository;

class PersonnalisationController extends AbstractController
{
    private $twig;
    private $category;


    public function __construct(Environment $twig, CategoryRepository $categoryRepository)
    {
        $this->twig = $twig;
        $this->category = $categoryRepository->findAll();
    }


    public function personnalisationClient($category, $product, Request $request)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['name' => $product]);
        $zoneDeMarquage =  $this->getDoctrine()->getRepository(ZoneDeMarquage::class)->findOneBy(['product_id' => $request->attributes->get('id')]);
        if ($product) {
            return $this->render('personnalisation/personnalisationClient.html.twig', [
                'controller_name' => 'PersonnalisationController',
                'categories' => $this->category,
                'theCategory' => $category,
                'theProduct' => $product,
                'zoneDeMarquage' => $zoneDeMarquage
            ]);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
            ]);
        }
    }

    public function personnalisationCheckInfo(Request $request, ZoneDeMarquageRepository $zoneDeMarquageRepository )
    {

        /*dump($request->request->all());
        die();*/
        $i = 0;
        foreach ($request->request->all() as $value) {
            if ($value < 50 && !empty($value)) {
                $this->addFlash('errorQuantity', "La quantité doit être égale à 50 ou plus");
                return $this->redirectToRoute('productClient', [
                    'category' => $request->attributes->get('category'),
                    'product' => $request->attributes->get('product'),
                    'id' => $request->attributes->get('id'),
                    'color' => $request->attributes->get('color'),
                ]);
            }else{
                $i++;
            }
        }
        if($i >= 1){
            /*dump($zoneDeMarquageRepository);
            die();*/
            return $this->personnalisationClient($request->attributes->get('category'),$request->attributes->get('product'), $request);
            /*return $this->redirectToRoute('personnalisationClient', [
                'category' => $request->attributes->get('category'),
                'product' => $request->attributes->get('product'),
                'id' => $request->attributes->get('id'),
                'color' => $request->attributes->get('color'),
                'zoneDeMarquage' => $zoneDeMarquageRepository
            ]);*/
        }
    }
}

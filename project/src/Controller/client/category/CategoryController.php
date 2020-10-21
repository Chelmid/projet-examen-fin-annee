<?php

namespace App\Controller\client\category;

use App\Entity\Category;
use App\Entity\Product;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private $category;


    public function __construct(CategoryService $categoryService)
    {
        $this->category = $categoryService->getFullCategories();
    }

    public function testApi()
    {
        $curl = curl_init('https://api.rawg.io/api/games');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($curl,CURLOPT_CAINFO, __DIR__.DIRECTORY_SEPARATOR.'cert.cer');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data_api = curl_exec($curl);
        if ($data_api === false) {
            var_dump(curl_error($curl));
        } else {
            $data_api = json_decode($data_api, true);
            return $data_api["results"];
        }
        curl_close($curl);
    }

    public function categoryClient($category)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['name' => $category]);
        $product = $this->getDoctrine()->getRepository(Product::class)->findAll();
        if (!$category) {
            // Si aucun category n'est trouvé, nous créons une exception
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig',[
                'categories' => $this->category,
            ]);
        } else {
            return $this->render('category/categoryClient.html.twig', [
                'controller_name' => 'CategoryController',
                'categories' => $this->category,
                'theCategory' => $category,
                'list_product' => $product,
            ]);
        }
    }
}

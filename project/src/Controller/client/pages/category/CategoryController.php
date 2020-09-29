<?php

namespace App\Controller\client\pages\category;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CategoryController extends AbstractController
{
    private $twig;
    private $category;


    public function __construct(Environment $twig, CategoryRepository $categoryRepository)
    {
        $this->twig = $twig;
        $this->category = $categoryRepository->findAll();
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

        if (!$category) {
            // Si aucun category n'est trouvé, nous créons une exception
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig',[
                'categories' => $this->category,
            ]);
        } else {
            return $this->render('client/pages/category/categoryClient.html.twig', [
                'controller_name' => 'CategoryController',
                'categories' => $this->category,
                'theCategory' => $category->getName(),
                'list_test_api' => $this->testApi(),
            ]);
        }
    }
}

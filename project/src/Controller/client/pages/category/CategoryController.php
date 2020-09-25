<?php

namespace App\Controller\client\pages\category;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CategoryController extends AbstractController
{
    private $twig;
    private $category;


    public function __construct( Environment $twig,CategoryRepository $categoryRepository)
    {
        $this->twig = $twig;
        $this->category = $categoryRepository->findAll();
    }


    public function categoryClient($category)
    {
        $curl = curl_init('https://api.rawg.io/api/games');
        //curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl,CURLOPT_CERTINFO,__DIR__.'/api_game.cer');
        //curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        $data_api = curl_exec($curl);
        dump($data_api);
        die();

        return $this->render('client/pages/category/categoryClient.html.twig', [
            'controller_name' => 'CategoryController',
            'categories'=> $this->category,
            'theCategory' => $category
        ]);
    }
}

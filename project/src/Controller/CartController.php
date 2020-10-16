<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartController extends AbstractController
{
    private $catagories;
    /**
     * @Route("{_locale}/cart", name="cart")
     */
    public function index(SessionInterface $session, ProductRepository $productRepository, Request $request, CategoryRepository $categoryRepository,TranslatorInterface $translator)
    {
        $this->category = $categoryRepository->findAll();

        $panier = $session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $ColorQuantite){
        $panierWithData[] = [
            'product' => $productRepository->find($id),
            'ColorQuantite' => $ColorQuantite,
        ];
    }

        //dd($panierWithData);

        if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {
            return $this->render('client/pages/cartClient.html.twig', [
                'categories' => $this->category,'items' => $panierWithData
            ]);
        } else {
            return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                'categories' => $this->category,
            ]);
        }
    }

    /**
     * @Route("{_locale}/cart/add/{id}", name="cart_add")
     */
    public function add($id, Request $request, SessionInterface $session, TranslatorInterface $translator)
    {


        //dd($request->request->all());
        //$session = $request->getSession()->clear();

        $panier = $session->get('panier', []);

        $panier[$id] = [];

        foreach ($request->request->all() as $key => $value) {
            if ($value != 0 || !empty($value)){
                array_push($panier[$id], ['color' => str_replace('_',"",$key), 'quantite' => intval($value)]);
            }
        }

        $session->set('panier', $panier);

        dd($panier);
    }
}

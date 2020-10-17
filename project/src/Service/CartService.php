<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add($id, Request $request){

        $panier = $this->session->get('panier', []);

        $panier[$id] = [];

        foreach ($request->request->all() as $key => $value) {
            if ($value != 0 || !empty($value)){
                array_push($panier[$id], ['color' => str_replace('_',"",$key), 'quantite' => intval($value)]);
            }
        }

        $this->session->set('panier', $panier);
    }

    public function remove($id, $request){


    }

    public function getfullCart(){
        $panier = $this->session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $ColorQuantite){
            $panierWithData[] = [
                'product' => $this->productRepository->find($id),
                'ColorQuantite' => $ColorQuantite,
            ];
        }
        return $panierWithData;
    }

    public function getTotal(){

    }

}

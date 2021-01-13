<?php

namespace App\Controller\client;

use App\Service\CartService;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OrderService;

class OrderController extends AbstractController
{

    protected $category;
    protected $cartService;

    public function __construct(CategoryService $categoryService, CartService $cartService){

        $this->category = $categoryService->getFullCategories();
        $this->cartService = $cartService;
    }

    /**
     * @Route("/order", name="order")
     */
    public function index(): Response
    {

        $panierWithData = $this->cartService->getfullCart();

        return $this->render('client/order/order.html.twig', [
            'controller_name' => 'OrderController',
            'categories' => $this->category,
            'items' =>  $panierWithData,
            'total' => $this->cartService->getTotal(),
            'totalAndImpression' => $this->cartService->getTotalAndPriceImpression(),
            'totalItem' => $this->cartService->getTotalItem(),
            'tva' => $this->cartService->getTva(),
            'priceImpression' => $this->cartService->getPriceImpression()
        ]);
    }

    /**
     * @Route("/order/confirmation", name="order_confirmation")
     */
    public function addOrder(Request $request, OrderService $orderService){

        $orderService->addOrder($request);

        $this->addFlash('Commande', 'Votre commande est bien enregistrée');
        return $this->redirectToRoute('homeClient');
    }
}

<?php

namespace App\Service;


use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\PanierProductRepository;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderService {

    private $panier;
    private $order;
    private $em;
    private $panierProduct;

    public function __construct(PanierRepository $panierRepository, OrderRepository $orderRepository, EntityManagerInterface $entityManager, PanierProductRepository $panierProductRepository)
    {
        $this->panier = $panierRepository;
        $this->order = $orderRepository;
        $this->em = $entityManager;
        $this->panierProduct = $panierProductRepository;
    }

    public function addOrder($request){

        $order = new Order();

        $date = new \DateTime();
        $date->modify('+ 1 hour');

        $this->em->persist($order->setPanier($this->panier->find($request->query->get('idPanier'))));
        $this->em->persist($order->setTotalPrice($request->query->get('total')));
        $this->em->persist($order->setDateOrder($date));
        $this->em->persist($order->setMethodPayement($request->request->get('payment-option')));
        $this->panier->find($request->query->get('idPanier'))->setIsOrder(true);
        $this->em->flush();

    }

}

<?php

namespace App\Service;


use App\Entity\Order;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\PanierProductRepository;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class OrderService {

    private $panier;
    private $order;
    private $em;
    private $panierProduct;
    private $security;

    public function __construct(PanierRepository $panierRepository, OrderRepository $orderRepository, EntityManagerInterface $entityManager, PanierProductRepository $panierProductRepository, Security $security)
    {
        $this->panier = $panierRepository;
        $this->order = $orderRepository;
        $this->em = $entityManager;
        $this->panierProduct = $panierProductRepository;
        $this->security = $security;
    }

    public function addOrder($request){

        $order = new Order();
        //date +1 heure
        $date = new \DateTime();
        $date->modify('+ 1 hour');

        //ajouter les données dans le order
        $this->em->persist($order->setPanier($this->panier->find($request->query->get('idPanier'))));
        $this->em->persist($order->setTotalPrice($request->query->get('total')));
        $this->em->persist($order->setDateOrder($date));
        $this->em->persist($order->setMethodPayement($request->request->get('payment-option')));
        $this->em->persist($order->setUser($this->security->getUser()));
        $this->panier->find($request->query->get('idPanier'))->setIsOrder(true);
        $this->em->flush();

    }

}

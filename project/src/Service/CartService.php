<?php

namespace App\Service;

use App\Entity\PanierProduct;
use App\Entity\Product;
use App\Entity\Panier;
use App\Repository\PanierProductRepository;
use App\Repository\PanierRepository;
use App\Repository\ProductRepository;
use App\Repository\ZoneDeMarquageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CartService
{

    protected $session;
    protected $productRepository;
    protected $entityManager;
    private $security;

    public function __construct(SessionInterface $session, ZoneDeMarquageRepository $zoneDeMarquageRepository, ProductRepository $productRepository, PanierRepository $panierRepository, EntityManagerInterface $entityManager, PanierProductRepository $panierProductRepository, Security $security)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
        $this->panierProductRepository = $panierProductRepository;
        $this->security = $security;
        $this->panierRepository = $panierRepository;
        $this->zoneDeMarquageRepository = $zoneDeMarquageRepository;
    }

    public function add($id, $color, Request $request, $i, $quantity)
    {

        $diese = str_replace('#', "", $color);
        $colorClear = str_replace('_', "", $diese);
        //dd($color);
        $panierProduct = new PanierProduct();
        $paniertest = new Panier();

        $productfind = $this->entityManager->getRepository(Product::class)->find($id);
        $panierProductOriginals = $this->panierProductRepository->findAll();
        $panierUser = $this->panierRepository->findOneByUser($this->security->getUser()->getId());

        if ($panierUser == null) {
            $this->entityManager->persist($paniertest->setUser($this->security->getUser()));
            $this->entityManager->persist($panierProduct->setPanier($paniertest));
            $this->entityManager->persist($panierProduct->setProduct($productfind));
            $this->entityManager->persist($panierProduct->setQuantity($quantity));
            $this->entityManager->persist($panierProduct->setColorAndImage($i));
            $this->entityManager->flush();
        } else {
            if ($panierProductOriginals != null) {
                $panierProductOriginals = $this->panierProductRepository->findOneByPanier($panierUser->getId());
                $this->entityManager->persist($panierProductOriginals->getPanier()->addPanier($panierProduct->setProduct($productfind)));
                $this->entityManager->persist($panierProduct->setQuantity($quantity));
                $this->entityManager->persist($panierProduct->setColorAndImage($i));
                $this->entityManager->flush();
            }
        }

    }


    public function delete($id, $color)
    {

        $datas = $this->panierProductRepository->findAll();

        foreach ($datas as $data) {
            if (count($datas) == 1) {
                $this->entityManager->remove($data->getPanier());
                foreach ($this->panierProductRepository->findById($id) as $value) {
                    $this->entityManager->remove($value);
                }
            } else {
                foreach ($this->panierProductRepository->findById($id) as $value) {
                    $this->entityManager->remove($value);
                }
            }
        }
        $this->entityManager->flush();
    }

    public function getfullCart()
    {

        $panierWithData = [];
        if ($this->panierRepository->findByUser($this->security->getUser()->getId())) {
            foreach ($this->panierRepository->findByUser($this->security->getUser()->getId()) as $panierId) {
                foreach ($this->panierProductRepository->findByPanier($panierId->getId()) as $value) {
                    $panierWithData[] =
                        $value;
                    $this->productRepository->findById($value->getProduct()->getId());
                    1;
                }
            }
        };
        //dd($panierWithData);
        return $panierWithData;
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->getfullCart() as $item) {

            $total += $item->getProduct()->getPrice() * $item->getQuantity();
        }

        return $total;
    }

    public function getTotalItem()
    {
        $compter = 0;

        foreach ($this->getfullCart() as $key => $item) {
            $compter += $item->getQuantity();

        }
        return $compter;
    }

    public function getTva()
    {
        $tva = $this->getTotal() * 0.2;
        $tva = $tva + $this->getTotal();
        return $tva = number_format($tva, 2, ',', ' ');
    }

    public function updateQuantite()
    {

    }

}

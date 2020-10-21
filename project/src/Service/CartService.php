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

    public function add($id, $color, Request $request, $i)
    {

        //$this->session->clear();
        //dd('effacer');

        /*$panier = $this->session->get('panier', []);

        $panier[$id] = [];

        $tab = [];

        foreach ($request->request->all() as $key => $value) {
            if ($value != 0 || !empty($value)) {
                $diese = str_replace('#', "", $key);
                array_push($panier[$id], ['color' => str_replace('_', "", $diese), 'quantite' => intval($value)]);
            }
        }

        $this->session->set('panier', $panier);*/
        $diese = str_replace('#', "", $color);
        $colorClear = str_replace('_', "", $diese);
        //dd($color);
        $panierProduct = new PanierProduct();
        $paniertest = new Panier();
        $product = new  Product();

        $productfind = $this->entityManager->getRepository(Product::class)->find($id);
        $panierProductOriginals = $this->panierProductRepository->findAll();
        $panierUser = $this->panierRepository->findOneByUser($this->security->getUser()->getId());


        foreach ($this->zoneDeMarquageRepository->findAll() as $value){

            if($value->getProductId()->getId() == $id){
                $product->setZoneDeMarquage($value);
            }
        };

//dd($this->zoneDeMarquageRepository->findAll());
        //->setZoneDeMarquage($productfind->getZoneDeMarquage())
        $image = explode(",", $productfind->getImage());
        $imageclear = str_replace("'","",$image);
        $product->setName($productfind->getName())
            ->setId($productfind->getId())
            ->setSKU($productfind->getSKU())
            ->setPrice($productfind->getPrice())
            ->setColor($colorClear)
            ->setImage($imageclear[$i])
            ->setCreateAt($productfind->getCreateAt())
            ->setUpdatedAt($productfind->getUpdatedAt())
            ->setDescription($productfind->getDescription())
            ->setCategory($productfind->getCategory())
            ->setQuantity($productfind->getQuantity());

        if ($panierUser == null) {
            $this->entityManager->persist($paniertest->setUser($this->security->getUser()));
            $this->entityManager->persist($panierProduct->setPanier($paniertest));
            $this->entityManager->persist($panierProduct->setProduct($product));
            $this->entityManager->persist($panierProduct->setColor($colorClear));
            $this->entityManager->flush();
        } else {
            if ($panierProductOriginals != null) {
                $panierProductOriginals = $this->panierProductRepository->findOneByPanier($panierUser->getId());
                $this->entityManager->persist($panierProductOriginals->getPanier()->addPanier($panierProduct->setProduct($product)));
                $this->entityManager->persist($panierProduct->setColor($colorClear));
                $this->entityManager->flush();
            }/*else{
                $this->entityManager->remove($panierUser);
                $this->entityManager->flush();
            }*/
        }

    }


    public function delete($id, $color)
    {

        /*$panier = $this->session->get('panier', []);
        $panierProduct = $this->panierProductRepository->findAll();

        foreach ($panierProduct as $key => $value) {

            if ($value->getProduct()->getId() == $id) {

                $this->entityManager->remove($value);
                $this->entityManager->flush();
                //dump($value->getProduct()->getId());
            }

        }
        //dd($panierProduct);

        if (!empty($panier[$id])) {
            foreach ($panier as $keyItem => $item)
                //dump($keyItem);
                //dump($item);
                foreach ($item as $keyInfo => $info) {
                    //dump($keyInfo);
                    // dump($info['color']);
                    //dump($panier[$id]);
                    if ($keyItem == $id && $color == $info['color']) {
                        //unset($keyInfo);
                        //dump($item[$keyInfo]);
                        unset($panier[$id][$keyInfo]);
                        //$this->entityManager->persist($panierProduct);
                        //$this->entityManager->flush();
                    }
                }
        } else {
            unset($panier[$id]);
        }

        $this->session->set('panier', $panier);
        //dd($panier);*/
        $product = new Product();
        $panierProduct = new PanierProduct();
        //$data = $this->panierProductRepository->findByProduct($id);
        $data = $this->panierProductRepository->findAll();
        $dataUser = $this->panierProductRepository->findAll();
        //dd($this->panierProductRepository->findByProduct($id));
//dd($product->removePanierProduct($this->panierProductRepository->findByProduct($id)));

        /*foreach ($this->panierProductRepository->findByProduct($id) as $value){
            //dd($value->getPanier());
            //dd($product->removePanierProduct($this->panierProductRepository->findByProduct($id)));
            if(!$value){

            }*/
        //dd($data);
        //dd($this->panierRepository->findOneByUser($this->security->getUser()->getId())->get);
        foreach ($data as $value) {
            if (count($data) == 1) {
                foreach ($dataUser as $value) {
                    $this->entityManager->remove($value->getPanier());
                    $this->entityManager->flush();
                    if ($value->getProduct()->getId() == $id) {
                        $this->entityManager->remove($value->getProduct());
                        $this->entityManager->flush();
                    }
                }
            } else {
                if ($value->getProduct()->getId() == $id) {
                    $this->entityManager->remove($value->getProduct());
                    $this->entityManager->flush();
                }
            }
        }
    }

    public function getfullCart()
    {

        /*$panier = $this->session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $ColorQuantite) {
            $panierWithData[] = [
                'product' => $this->productRepository->find($id),
                'ColorQuantite' => $ColorQuantite,
            ];
        }
        //dd($panier);
        return $panierWithData;*/
        $data = $this->panierProductRepository->findAll();

        $panierWithData = [];
        if ($this->panierRepository->findByUser($this->security->getUser()->getId())) {
            foreach ($this->panierRepository->findByUser($this->security->getUser()->getId()) as $panierId) {
                foreach ($this->panierProductRepository->findByPanier($panierId->getId()) as $value) {
                    $panierWithData[] =
                        $value;
                    $this->productRepository->findById($value->getProduct()->getId());
                }
            }
        };
        return $panierWithData;
    }

    public function getTotal()
    {

    }

    public function getTotalItem()
    {

    }

    public function addPersonnalisation()
    {

    }

}

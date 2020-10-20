<?php

namespace App\Service;

use App\Entity\PanierProduct;
use App\Entity\Product;
use App\Entity\Panier;
use App\Repository\PanierProductRepository;
use App\Repository\PanierRepository;
use App\Repository\ProductRepository;
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

    public function __construct(SessionInterface $session, ProductRepository $productRepository, PanierRepository $panierRepository, EntityManagerInterface $entityManager, PanierProductRepository $panierProductRepository, Security $security)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
        $this->panierProductRepository = $panierProductRepository;
        $this->security = $security;
        $this->panierRepository = $panierRepository;
    }

    public function add($id, Request $request)
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

        $panierProduct = new PanierProduct();
        $paniertest = new Panier();
        $product = $this->entityManager->getRepository(Product::class)->find($id);
        $userInPanier = $this->entityManager->getRepository(Panier::class)->findAll();
        $panierProductOriginals = $this->panierProductRepository->findAll();

        //dd($paniertest->getPanier());
        $panierUser = $this->panierRepository->findOneByUser($this->security->getUser()->getId());
        //$panierUser2 = $this->panierRepository->findOneById();
        $panierProductOriginals = $this->panierProductRepository->findOneByPanier($panierUser->getId());
        //$paniertest->setUser($this->security->getUser());

        /*foreach ($request->request->all() as $key => $value) {
            if ($value != 0 || !empty($value)) {

        }*/
        //dd($panierUser->getId());

        if($panierUser == null){
            $this->entityManager->persist($paniertest->setUser($this->security->getUser()));
            $this->entityManager->persist($panierProduct->setPanier($paniertest));
            $this->entityManager->persist($panierProduct->setProduct($product));
            $this->entityManager->flush();
        }else{
            if($panierProductOriginals != null){
                $this->entityManager->persist($panierProductOriginals->getPanier()->addPanier($panierProduct->setProduct($product)));
                $this->entityManager->flush();
            }else{
                $this->entityManager->remove($panierUser);
                $this->entityManager->flush();
            }
        }

        /*foreach ($panierProductOriginals as $key => $panierProductOriginal) {
            if (empty($panierProductOriginal->getPanier()->getUser()->getId())) {
                $this->entityManager->persist($paniertest->setUser($this->security->getUser()));
                //$this->entityManager->persist($paniertest->addPanier($panierProduct));
                $this->entityManager->persist($panierProduct->setPanier($paniertest));
                $this->entityManager->persist($panierProduct->setProduct($product));
                $this->entityManager->flush();
            } else {
                $this->entityManager->persist($panierProductOriginal->getPanier()->addPanier($panierProduct->setProduct($product)));
                $this->entityManager->flush();
            }
        }*/
    }


    public function delete($id, $color)
    {

        $panier = $this->session->get('panier', []);
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
        //dd($panier);
    }

    public function getfullCart()
    {
        $panier = $this->session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $ColorQuantite) {
            $panierWithData[] = [
                'product' => $this->productRepository->find($id),
                'ColorQuantite' => $ColorQuantite,
            ];
        }
        //dd($panier);
        return $panierWithData;
    }

    public function getTotal()
    {

    }

    public function addPersonnalisation()
    {

    }

}

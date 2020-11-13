<?php

namespace App\Service;

use App\Entity\PanierProduct;
use App\Entity\Personnalisation;
use App\Entity\Product;
use App\Entity\Panier;
use App\Repository\PanierProductRepository;
use App\Repository\PanierRepository;
use App\Repository\PersonnalisationRepository;
use App\Repository\PriceImpressionRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
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
    protected $personnalisation;
    protected $priceImpression;
    protected $panierProductRepository;
    protected $userRepository;

    public function __construct(SessionInterface $session, UserRepository $userRepository, PriceImpressionRepository $priceImpressionRepository, ZoneDeMarquageRepository $zoneDeMarquageRepository, PersonnalisationRepository $personnalisationRepository, ProductRepository $productRepository, PanierRepository $panierRepository, EntityManagerInterface $entityManager, PanierProductRepository $panierProductRepository, Security $security)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
        $this->panierProductRepository = $panierProductRepository;
        $this->security = $security;
        $this->panierRepository = $panierRepository;
        $this->zoneDeMarquageRepository = $zoneDeMarquageRepository;
        $this->personnalisation = $personnalisationRepository;
        $this->priceImpression = $priceImpressionRepository;
        $this->userRepository = $userRepository;
    }

    public function add($id, $color, Request $request, $i, $quantity)
    {

        $diese = str_replace('#', "", $color);
        $colorClear = str_replace('_', "", $diese);

        // instance
        $panierProduct = new PanierProduct();
        $panier = new Panier();

        $productfind = $this->entityManager->getRepository(Product::class)->find($id);
        $panierProductOriginals = $this->panierProductRepository->findAll();
        $panierUser = $this->panierRepository->findOneByUser($this->security->getUser()->getId());

        // panier vide
        if ($panierUser == null) {
            $this->entityManager->persist($panier->setUser($this->security->getUser()));
            $this->entityManager->persist($panierProduct->setPanier($panier));
            $this->entityManager->persist($panierProduct->setProduct($productfind));
            $this->entityManager->persist($panierProduct->setQuantity($quantity));
            $this->entityManager->persist($panierProduct->setColorAndImage($i));
            $this->entityManager->persist($panier->setIsOrder(false));

        } else {
            // panier pas vide
            if ($panierProductOriginals != null) {
                //panier is_order recherche que des false
                if (empty($this->panierRepository->findByisOrder(false))) {
                    $this->entityManager->persist($panier->setUser($this->security->getUser()));
                    $this->entityManager->persist($panierProduct->setPanier($panier));
                    $this->entityManager->persist($panierProduct->setProduct($productfind));
                    $this->entityManager->persist($panierProduct->setQuantity($quantity));
                    $this->entityManager->persist($panierProduct->setColorAndImage($i));
                    $this->entityManager->persist($panier->setIsOrder(false));

                } else {

                    //recherche du panier avec le order false

                    $userPanier = $this->panierRepository->panierCheck($this->security->getUser()->getId(), 'false')->getId();
                    //panier cree mais panierProduct vide
                    if (empty($this->panierProductRepository->panierProductCheckArray($userPanier))) {
                        $panierProduct->setQuantity($quantity);
                        $panierProduct->setColorAndImage($i);
                        $this->entityManager->persist($this->panierRepository->panierCheck($this->security->getUser()->getId(), 'false')->addPanier($panierProduct->setProduct($productfind)));
                    } else {
                        // on continue sur le panier excitant
                        $this->entityManager->persist($this->panierProductRepository->panierProductCheck($userPanier)->getPanier()->addPanier($panierProduct->setProduct($productfind)));
                        $this->entityManager->persist($panierProduct->setQuantity($quantity));
                        $this->entityManager->persist($panierProduct->setColorAndImage($i));
                    }
                }
            }
        }
        $this->entityManager->flush();
    }


    public function delete($id, $color)
    {
        //recheche le panier de l'utilisateur
        $userPanier = $this->panierRepository->panierCheck($this->security->getUser()->getId(), 'false')->getId();

        //parcourir en le panierProduct array par rapport à l'utilisateur
        foreach ($this->panierProductRepository->panierProductCheckArray($userPanier) as $value) {
            //id identique
            if ($value->getId() == $id) {
                //personnalisation non vide
                if ($value->getPersonnalisation() != null) {
                    $this->entityManager->remove($value->getPersonnalisation());
                } else {
                    //vide personnalisation
                    $this->entityManager->remove($value);
                    $this->entityManager->remove($this->panierRepository->panierCheck($this->security->getUser()->getId(), 'false'));
                }
            }
        }

        $this->entityManager->flush();
    }

    public function getfullCart()
    {
        //tableau du panier
        $panierWithData = [];
        // l'utilisateur correspondant
        if ($this->panierRepository->findByUser($this->security->getUser()->getId())) {
            // parcourir pour trouver le panier
            foreach ($this->panierRepository->findByUser($this->security->getUser()->getId()) as $panierId) {
                foreach ($this->panierProductRepository->findByPanier($panierId->getId()) as $value) {
                    //les panier de l'utilisateur avec les is_order false
                    if ($value->getPanier()->getIsOrder() == false) {
                        // si pas de personnalisation
                        if ($value->getPersonnalisation() == null) {
                            //mettre dans le panier la liste
                            $panierWithData[] =
                                $value;
                            $this->productRepository->findById($value->getProduct()->getId());
                        } else {
                            //mettre dans le panier la lisate avec la personnalisation
                            $panierWithData[] =
                                $value;
                            $this->productRepository->findById($value->getProduct()->getId());
                            $this->personnalisation->findById($value->getPersonnalisation()->getId());
                            foreach ($this->personnalisation->findById($value->getPersonnalisation()->getId()) as $value) {
                                $this->priceImpression->findById($value->getPriceImpression()->getId());
                            }
                        }
                    }
                }
            }
        }
        return $panierWithData;
    }

    public function getTotal()
    {
        $total = 0;
        // parcours la liste des products dans le panier
        foreach ($this->getfullCart() as $item) {
            //addition
            $total += $item->getProduct()->getPrice() * $item->getQuantity();
        }

        return $total;
    }

    public function getTotalAndPriceImpression()
    {
        // total et avec le total de l'impression
        return $this->getPriceImpression() + $this->getTotal();
    }

    public function getPriceImpression()
    {
        $totalImpression = 0;
        // parcours la liste des products dans le panier
        foreach ($this->getfullCart() as $item) {
            //personnalisation pas vide
            if ($item->getPersonnalisation() != null) {
                $totalImpression += $item->getPersonnalisation()->getPriceImpression()->getPrice() * $item->getQuantity();
            }
        }
        return $totalImpression;
    }

    public function getTotalItem()
    {
        $compter = 0;
        // parcours la liste des products dans le panier
        foreach ($this->getfullCart() as $key => $item) {
            //le nombre de produit
            $compter += $item->getQuantity();

        }
        return $compter;
    }

    public function getTva()
    {
        //le total avec la tva
        $tva = $this->getTotalAndPriceImpression() * 0.2;
        $tva = $tva + $this->getTotalAndPriceImpression();
        //decimal avec 2 chiffre apres la virgule
        return $tva = number_format($tva, 2, ',', ' ');
    }

    public function updateQuantite($id, $quantity)
    {
        $panierProduct = $this->panierProductRepository->find($id);
        $quantityBdd = "";
        //parcourir les quantités des produits
        foreach ($this->productRepository->findById($panierProduct->getProduct()->getId()) as $value) {
            //une chaîne de caractères en segments
            $quantityBase = explode("','", $value->getQuantity());
            foreach ($quantityBase as $key => $value) {
                if ($key == $panierProduct->getColorAndImage()) {
                    $quantityBdd = str_replace("'", "", $value);
                }
            }
            //modifier dans la bdd
            if ($quantityBdd >= $quantity) {
                $panierProduct->setQuantity($quantity);
                $this->entityManager->flush();
            } else {
                $this->addFlash('success', 'Article Created! Knowledge is power!');
            }
        }
    }

    public function addPersonnalisation($id, $request, $quantity, $i)
    {

        $panierProduct = new PanierProduct();
        $panier = new Panier();
        $personnalisation = new Personnalisation();

        $productfind = $this->entityManager->getRepository(Product::class)->find($id);
        $panierProductOriginals = $this->panierProductRepository->findAll();
        $panierUser = $this->panierRepository->findOneByUser($this->security->getUser()->getId());
        $priceImpression = $this->priceImpression->findByType($request->request->get('impression'));

        //mettre les données de la personnalistion
        $personnalisation
            ->setDatauri($request->request->get('dataFile'))
            ->setFile($request->request->get('file'))
            ->setHeight($request->request->get('logoHeight'))
            ->setWidth($request->request->get('logoWidth'))
            ->setLeftPosition($request->request->get('logoLeft'))
            ->setTopPosition($request->request->get('logoTop'))
            ->setImpression($request->request->get('impression'));

        foreach ($priceImpression as $value) {
            $personnalisation->setPriceImpression($value);
        }

        //panier vide
        if ($panierUser == null) {

            $this->entityManager->persist($panier->setUser($this->security->getUser()));
            $this->entityManager->persist($panierProduct->setPanier($panier));
            $this->entityManager->persist($panierProduct->setProduct($productfind));
            $this->entityManager->persist($panierProduct->setQuantity($quantity));
            $this->entityManager->persist($panierProduct->setColorAndImage($i));
            $this->entityManager->persist($panierProduct->setPersonnalisation($personnalisation));
            $this->entityManager->persist($panier->setIsOrder(false));
            $this->entityManager->flush();
        } else {
            //panier pas vide
            if ($panierProductOriginals != null) {
                //les paniers avec les champs false
                if (empty($this->panierRepository->findByisOrder(false))) {
                    $this->entityManager->persist($panier->setUser($this->security->getUser()));
                    $this->entityManager->persist($panierProduct->setPanier($panier));
                    $this->entityManager->persist($panierProduct->setProduct($productfind));
                    $this->entityManager->persist($panierProduct->setQuantity($quantity));
                    $this->entityManager->persist($panierProduct->setColorAndImage($i));
                    $this->entityManager->persist($panierProduct->setPersonnalisation($personnalisation));
                    $this->entityManager->persist($panier->setIsOrder(false));
                    $this->entityManager->flush();

                } else {
                    //recherche du panier avec le order false
                    $userPanier = $this->panierRepository->panierCheck($this->security->getUser()->getId(), 'false')->getId();
                    //panierProduct avec 0 product
                    if (empty($this->panierProductRepository->panierProductCheckArray($userPanier))) {
                        $panierProduct->setQuantity($quantity);
                        $panierProduct->setColorAndImage($i);
                        $this->entityManager->persist($this->panierRepository->panierCheck($this->security->getUser()->getId(), 'false')->addPanier($panierProduct->setProduct($productfind)));
                        $this->entityManager->persist($panierProduct->setPersonnalisation($personnalisation));
                        $this->entityManager->flush();
                    } else {
                        //ajouter dans le panierProduct excitant
                        $this->entityManager->persist($this->panierProductRepository->panierProductCheck($userPanier)->getPanier()->addPanier($panierProduct->setProduct($productfind)));
                        $this->entityManager->persist($panierProduct->setPersonnalisation($personnalisation));
                        $this->entityManager->persist($panierProduct->setQuantity($quantity));
                        $this->entityManager->persist($panierProduct->setColorAndImage($i));
                        $this->entityManager->flush();
                    }

                }
            }
        }
        //dd('ici');
        //$this->entityManager->flush();
    }

    public function updatePersonnalisation($id, $request, $personId)
    {
        $personnalisation = $this->personnalisation->find($personId);
        $priceImpression = $this->priceImpression->findByType($request->request->get('impression'));

        // mettre des données dans la personnalisation

        //file champs vide
        if ($request->request->get('file') == '') {
            $personnalisation->setFile($request->request->get('nameFile'));
        } else {
            // 2eme champs de avec un nom
            $personnalisation->setFile($request->request->get('file'));
        }
        $personnalisation->setDatauri($request->request->get('dataFile'));
        $personnalisation->setWidth($request->request->get('logoWidth'));
        $personnalisation->setHeight($request->request->get('logoHeight'));
        $personnalisation->setTopPosition($request->request->get('logoTop'));
        $personnalisation->setLeftPosition($request->request->get('logoLeft'));
        $personnalisation->setImpression($request->request->get('impression'));
        //parcourir le bon price impression
        foreach ($priceImpression as $value) {
            $personnalisation->setPriceImpression($value);
        }
        $this->entityManager->flush();
    }

    public function addPersonnalisationForNull($id, $request, $quantity, $i)
    {

        $userPanier = $this->panierRepository->panierCheck($this->security->getUser()->getId(), 'false')->getId();
        $personnalisation = new Personnalisation();
        // ajouter les données dans la personnalisation
        $personnalisation
            ->setDatauri($request->request->get('dataFile'))
            ->setFile($request->request->get('file'))
            ->setHeight($request->request->get('logoHeight'))
            ->setWidth($request->request->get('logoWidth'))
            ->setLeftPosition($request->request->get('logoLeft'))
            ->setTopPosition($request->request->get('logoTop'))
            ->setImpression($request->request->get('impression'));

        foreach ($this->priceImpression->findByType($request->request->get('impression')) as $value) {
            $personnalisation->setPriceImpression($value);
        }
        // ajoute dans la base de données
        $this->entityManager->persist($this->panierProductRepository->panierProductCheck($userPanier)->setPersonnalisation($personnalisation));
        $this->entityManager->flush();
    }
}

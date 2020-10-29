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

    public function __construct(SessionInterface $session, PriceImpressionRepository $priceImpressionRepository, ZoneDeMarquageRepository $zoneDeMarquageRepository, PersonnalisationRepository $personnalisationRepository, ProductRepository $productRepository, PanierRepository $panierRepository, EntityManagerInterface $entityManager, PanierProductRepository $panierProductRepository, Security $security)
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
    }

    public function add($id, $color, Request $request, $i, $quantity)
    {

        $diese = str_replace('#', "", $color);
        $colorClear = str_replace('_', "", $diese);
        //dd($color);
        $panierProduct = new PanierProduct();
        $panier = new Panier();

        $productfind = $this->entityManager->getRepository(Product::class)->find($id);
        $panierProductOriginals = $this->panierProductRepository->findAll();
        $panierUser = $this->panierRepository->findOneByUser($this->security->getUser()->getId());

        if ($panierUser == null) {
            $this->entityManager->persist($panier->setUser($this->security->getUser()));
            $this->entityManager->persist($panierProduct->setPanier($panier));
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
                    if ($value->getPersonnalisation() != null) {
                        $this->entityManager->remove($value->getPersonnalisation());
                    }
                    $this->entityManager->remove($value);
                }
            } else {
                foreach ($this->panierProductRepository->findById($id) as $value) {
                    if ($value->getId() == $id) {
                        $this->entityManager->remove($value->getPersonnalisation());
                        $this->entityManager->remove($value);
                    }
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
                    if ($value->getPersonnalisation() == null) {
                        $panierWithData[] =
                            $value;
                        $this->productRepository->findById($value->getProduct()->getId());
                    } else {
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
        };


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

    public function getTotalAndPriceImpression()
    {
        return $this->getPriceImpression() + $this->getTotal();
    }

    public function getPriceImpression()
    {
        $totalImpression = 0;

        foreach ($this->getfullCart() as $item) {

            if ($item->getPersonnalisation() != null) {
                $totalImpression += $item->getPersonnalisation()->getPriceImpression()->getPrice() * $item->getQuantity();
            }
        }
        return $totalImpression;
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
        $tva = $this->getTotalAndPriceImpression() * 0.2;
        $tva = $tva + $this->getTotalAndPriceImpression();
        return $tva = number_format($tva, 2, ',', ' ');
    }

    public function updateQuantite($id, $quantity)
    {
        $panierProduct = $this->panierProductRepository->find($id);
        $quantityBdd = "";

        foreach ($this->productRepository->findById($panierProduct->getProduct()->getId()) as $value) {
            $quantityBase = explode("','", $value->getQuantity());

            foreach ($quantityBase as $key => $value) {
                if ($key == $panierProduct->getColorAndImage()) {
                    $quantityBdd = str_replace("'", "", $value);
                }
            }

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

        if ($panierUser == null) {
            $this->entityManager->persist($panier->setUser($this->security->getUser()));
            $this->entityManager->persist($panierProduct->setPanier($panier));
            $this->entityManager->persist($panierProduct->setProduct($productfind));
            $this->entityManager->persist($panierProduct->setQuantity($quantity));
            $this->entityManager->persist($panierProduct->setColorAndImage($i));
            $this->entityManager->persist($panierProduct->setPersonnalisation($personnalisation));
            $this->entityManager->flush();
        } else {
            if ($panierProductOriginals != null) {
                $panierProductOriginals = $this->panierProductRepository->findOneByPanier($panierUser->getId());
                $this->entityManager->persist($panierProductOriginals->getPanier()->addPanier($panierProduct->setProduct($productfind)));
                //$this->entityManager->persist($panierProduct->setPersonnalisation($this->panierProductRepository->findOneByPanier($panierUser->getId())->getPersonnalisation()));
                $this->entityManager->persist($panierProduct->setPersonnalisation($personnalisation));
                $this->entityManager->persist($panierProduct->setQuantity(strval($quantity)));
                $this->entityManager->persist($panierProduct->setColorAndImage($i));
                $this->entityManager->flush();
            }
        }
    }

    public function updatePersonnalisation($id, $request, $personId)
    {
        $personnalisation = $this->personnalisation->find($personId);
        $priceImpression = $this->priceImpression->findByType($request->request->get('impression'));

        if ($request->request->get('file') == '') {
            $personnalisation->setFile($request->request->get('nameFile'));
        } else {
            $personnalisation->setFile($request->request->get('file'));
        }
        $personnalisation->setDatauri($request->request->get('dataFile'));
        $personnalisation->setWidth($request->request->get('logoWidth'));
        $personnalisation->setHeight($request->request->get('logoHeight'));
        $personnalisation->setTopPosition($request->request->get('logoTop'));
        $personnalisation->setLeftPosition($request->request->get('logoLeft'));
        $personnalisation->setImpression($request->request->get('impression'));
        foreach ($priceImpression as $value) {
            $personnalisation->setPriceImpression($value);
        }
        $this->entityManager->flush();
    }

    public function addPersonnalisationForNull($id, $request, $quantity, $i){

        $panierUser = $this->panierRepository->findOneByUser($this->security->getUser()->getId());
        $panierProduct = $this->panierProductRepository->findByPanier($panierUser->getId());
        $personnalisation = new Personnalisation();

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

        foreach($panierProduct as $value){
            if($value->getColorAndImage() == $request->query->get('color') &&  $value->getProduct()->getId() == $request->attributes->get('id')){
                $this->entityManager->persist($value->setPersonnalisation($personnalisation));
            }
        }
        $this->entityManager->flush();
    }
}

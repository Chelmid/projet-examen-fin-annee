<?php

namespace App\Controller\client\cart;

use App\Repository\CategoryRepository;
use App\Service\CartService;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartController extends AbstractController
{
    protected $catagory;
    protected $security;

    public function __construct(Security $security, CategoryService $categoryService)
    {
        $this->security = $security;
        $this->category = $categoryService->getFullCategories();
    }

    /**
     * @Route("{_locale}/cart", name="cart")
     */
    public function index(CartService $cartService, Request $request, CategoryRepository $categoryRepository, TranslatorInterface $translator)
    {

        if ($this->security->getUser() != null) {

            $panierWithData = $cartService->getfullCart();

            if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {
                return $this->render('cart/cartClient.html.twig', [
                    'categories' => $this->category,
                    'items' => $panierWithData,
                    'total' => $cartService->getTotal(),
                    'totalItem' => $cartService->getTotalItem(),
                    'tva' => $cartService->getTva()
                ]);
            } else {
                return $this->render('bundles/TwigBundle/Execption/error404.html.twig', [
                    'categories' => $this->category,
                ]);
            }
        } else {
            return $this->RedirectToRoute('app_login');
        }
    }

    /**
     * @Route("{_locale}/cart/add/{id}", name="cart_add")
     */
    public function add($id, Request $request, CartService $cartService, TranslatorInterface $translator)
    {
        if ($this->security->getUser() != null) {
            $i = 0;
            foreach ($request->request->all() as $color => $value) {
                if ($value != 0 || !empty($value)) {
                    $cartService->add($id, $color, $request, $i);
                }
                $i++;
            }
            return $this->redirectToRoute('cart');
        }else{
            return $this->RedirectToRoute('app_login');
        }
    }

    /**
     * @Route("{_locale}/cart/delete/{id}/{color}", name="cart_delete")
     */
    public function delete($id, $color, CartService $cartService, TranslatorInterface $translator)
    {
        $cartService->delete($id, $color);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("{_locale}/cart/add/Personnalisation/{id}", name="cart_add_Personnalisation")
     */
    public function addPresonnalisation($id, Request $request, CartService $cartService, TranslatorInterface $translator)
    {
        dd('ici');

    }
}

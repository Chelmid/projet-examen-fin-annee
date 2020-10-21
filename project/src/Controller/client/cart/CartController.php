<?php

namespace App\Controller\client\cart;

use App\Repository\CategoryRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class CartController extends AbstractController
{
    private $catagories;

    /**
     * @Route("{_locale}/cart", name="cart")
     */
    public function index(CartService $cartService, Request $request, CategoryRepository $categoryRepository, TranslatorInterface $translator, Security $security)
    {
        $this->category = $categoryRepository->findAll();

        if ($security->getUser() != null) {

            $panierWithData = $cartService->getfullCart();

            if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {
                return $this->render('cart/cartClient.html.twig', [
                    'categories' => $this->category, 'items' => $panierWithData
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
        foreach ($request->request->all() as $color => $value) {
            if ($value != 0 || !empty($value)) {
                $cartService->add($id, $color, $request);
            }
        }
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("{_locale}/cart/delete/{id}/{color}", name="cart_delete")
     */
    public function delete($id, $color, CartService $cartService, TranslatorInterface $translator)
    {
        $cartService->delete($id, $color);

        return $this->redirectToRoute('cart');
    }
}

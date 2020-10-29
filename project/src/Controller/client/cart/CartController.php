<?php

namespace App\Controller\client\cart;

use App\Repository\CategoryRepository;
use App\Service\CartService;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    protected $category;
    protected $security;
    protected $save;

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

            //dd($panierWithData);

            if ($request->getLocale() == 'fr' || $request->getLocale() == 'en' || $request->getLocale() == 'es') {
                return $this->render('cart/cartClient.html.twig', [
                    'categories' => $this->category,
                    'items' => $panierWithData,
                    'total' => $cartService->getTotal(),
                    'totalAndImpression' => $cartService->getTotalAndPriceImpression(),
                    'totalItem' => $cartService->getTotalItem(),
                    'tva' => $cartService->getTva(),
                    'priceImpression' => $cartService->getPriceImpression()
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
                    $cartService->add($id, $color, $request, $i, $value);
                }
                $i++;
                if ($i == 0) {
                    return $this->redirect($request->headers->get('referer'));
                }
            }
            return $this->redirectToRoute('cart');
        } else {
            return $this->RedirectToRoute('app_login');
        }
    }

    /**
     * @Route("{_locale}/cart/delete/{id}/{color}", name="cart_delete")
     */
    public function delete($id, $color, Request $request, CartService $cartService, TranslatorInterface $translator)
    {
        $cartService->delete($id, $color);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("cart/update/{id}/{newQuantity}", name="cart_update")
     */
    public function update($id, $newQuantity, Request $request, CartService $cartService, TranslatorInterface $translator)
    {
        $cartService->updateQuantite($id, $newQuantity, $request);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("{_locale}/cart/add/Personnalisation/{id}", name="cart_add_Personnalisation")
     */
    public function addPresonnalisation($id, Request $request, CartService $cartService, TranslatorInterface $translator)
    {

        if (empty($request->request->get('dataFile')) || $request->request->get('dataFile') == '') {
            if (empty($request->query->get('productSelectionner'))) {
                foreach ($request->query->get('productSelectionner') as $key => $value) {
                    foreach ($value as $keyColor => $value) {
                        foreach ($value as $key => $value) {
                            $cartService->add($id, $key, $request, $keyColor, $value);
                        }
                    }
                }
            }
        } else {
            if (!empty($request->query->get('productSelectionner'))) {
                foreach ($request->query->get('productSelectionner') as $key => $value) {
                    foreach ($value as $keyColor => $value) {
                        foreach ($value as $key => $value) {
                            $cartService->addPersonnalisation($id, $request, $value, $keyColor);
                        }
                    }
                }
            }
            if ($request->query->get('personnalisation') == 'null') {
                $cartService->addPersonnalisationForNull($id, $request, $request->query->get('quantity'), $request->query->get('color'));
            }

        }
        if (!empty($request->query->get('personId'))) {
            $cartService->updatePersonnalisation($id, $request, $request->query->get('personId'));
        }
        return $this->redirectToRoute('cart');
    }
}

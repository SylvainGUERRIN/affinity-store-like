<?php


namespace App\Controller;


use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @param CartService $cartService
     * @return Response
     * @Route("/panier", name="cart")
     */
    public function cart(CartService $cartService): Response
    {

        $panierWithData = $cartService->getFullCart();

        $total = $cartService->getTotalPrice();
        $quantityProducts = $cartService->getQuantity();

        return $this->render('site/boutique/cart.html.twig',[
            'quantityProducts' => $quantityProducts,
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

    /**
     * @route("/panier/add/{id}", name="cart_add")
     * @param $id
     * @param Request $request
     * @param CartService $cartService
     * @return Response
     */
    public function add($id, Request $request, CartService $cartService): Response
    {
        if($request->isXmlHttpRequest()){
            $result = $cartService->add($id);
            $total = array_sum($result);
            return new JsonResponse($total);
        }
    }

    /**
     * @route("/panier/less/{id}", name="cart_less")
     * @param $id
     * @param Request $request
     * @param CartService $cartService
     * @return Response
     */
    public function less($id, Request $request, CartService $cartService): Response
    {
        if($request->isXmlHttpRequest()){
            $result = $cartService->less($id);
            $total = array_sum($result);
            return new JsonResponse($total);
        }
    }

    /**
     * @route("/panier/remove/{id}", name="cart_remove")
     * @param $id
     * @param CartService $cartService
     * @param Request $request
     * @return Response
     */
    public function remove($id, CartService $cartService, Request $request): Response
    {
        if($request->isXmlHttpRequest()){
            $cartService->remove($id);
            $total = $cartService->getTotalPrice();
            $panierWithData = $cartService->getFullCart();
            $quantityProducts = $cartService->getQuantity();
        }
        return $this->render('user/partials/_cart-tab.html.twig', [
            'quantityProducts' => $quantityProducts,
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

    /**
     * @route("/panier/less-tab/{id}", name="cart_tab_less")
     * @param $id
     * @param CartService $cartService
     * @param Request $request
     * @return Response
     */
    public function lessTabCart($id, CartService $cartService, Request $request): Response
    {
        if($request->isXmlHttpRequest()){
            $cartService->less($id);
            $total = $cartService->getTotalPrice();
            $panierWithData = $cartService->getFullCart();
            $quantityProducts = $cartService->getQuantity();
        }
        return $this->render('user/partials/_cart-tab.html.twig', [
            'quantityProducts' => $quantityProducts,
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

    /**
     * @route("/panier/add-tab/{id}", name="cart_tab_add")
     * @param $id
     * @param CartService $cartService
     * @param Request $request
     * @return Response
     */
    public function addTabCart($id, CartService $cartService, Request $request): Response
    {
        if($request->isXmlHttpRequest()){
            $cartService->add($id);
            $total = $cartService->getTotalPrice();
            $panierWithData = $cartService->getFullCart();
            $quantityProducts = $cartService->getQuantity();
        }
        return $this->render('user/partials/_cart-tab.html.twig', [
            'quantityProducts' => $quantityProducts,
            'items' => $panierWithData,
            'total' => $total
        ]);
    }
}

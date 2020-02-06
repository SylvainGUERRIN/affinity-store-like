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
     * @param SessionInterface $session
     * @param ProductRepository $productRepository
     * @param CartService $cartService
     * @return Response
     * @Route("/panier", name="cart")
     */
    public function cart(CartService $cartService): Response
    {
        /*$panier = $session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $id => $quantity){
            $panierWithData[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantity
            ];
        }*/
        $panierWithData = $cartService->getFullCart();

//        $total = 0;
//        $quantityProducts = 0;
//        foreach ($panierWithData as $item){
//            $totalItem = $item['product']->getPrice() * $item['quantity'];
//            $total += $totalItem;
//            $quantityProducts += $item['quantity'];
//        }
        $total = $cartService->getTotal();
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
     * @param SessionInterface $session
     * @param Request $request
     * @param CartService $cartService
     * @return Response
     */
    public function add($id, Request $request, CartService $cartService): Response
    {
        if($request->isXmlHttpRequest()){
            /*$panier = $session->get('panier', []);
            if(!empty($panier[$id])){
                $panier[$id]++;
                $session->set('panier', $panier);
                $total = array_sum($panier);
            }else{
                $panier[$id] = 1;
                $session->set('panier', $panier);
                $total = array_sum($panier);
            }*/
            $result = $cartService->add($id);
            $total = array_sum($result);
            return new JsonResponse($total);
        }
//        return $this->render('user/partials/_cart.html.twig', [
//            'number' => $total,
//        ]);
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
            $result = $cartService->remove($id);
            $total = array_sum($result);
            $panierWithData = $cartService->getFullCart();
            $quantityProducts = $cartService->getQuantity();
            /*if (!empty($panier[$id])) {
                if ($panier[$id] === 1) {
                    unset($panier[$id]);
                }else{
                    $panier[$id]--;
                }
                $session->set('panier', $panier);
            }
            if(empty($panier)){
                $total = 0;
                $quantityProducts = 0;
                $panierWithData = [];
            }else {
                $panierWithData = [];
                foreach ($panier as $element => $quantity) {
                    $panierWithData[] = [
                        'product' => $productRepository->find($element),
                        'quantity' => $quantity
                    ];
                }

                $total = 0;
                $quantityProducts = 0;
                foreach ($panierWithData as $item) {
                    $totalItem = $item['product']->getPrice() * $item['quantity'];
                    $total += $totalItem;
                    $quantityProducts += $item['quantity'];
                }
            }*/
        }
        return $this->render('user/partials/_cart-tab.html.twig', [
            'quantityProducts' => $quantityProducts,
            'items' => $panierWithData,
            'total' => $total
        ]);
    }
}

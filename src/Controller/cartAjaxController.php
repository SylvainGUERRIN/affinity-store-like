<?php


namespace App\Controller;


use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class cartAjaxController extends AbstractController
{
    /**
     * fonction en ajax pour ajouter un article dans la fenetre modale
     * @Route("/ajax/article/add-cart", name="article_add_cart")
     * @param Request $request
     * @param CartService $cartService
     * @return Response
     */
    public function articleAddCart(Request $request, CartService $cartService): Response
    {
        if($request->isXmlHttpRequest()) {
            //$panier = $session->get('panier', []);
//            dd($panier);
            //pas d'ajout simplement mis à jour du panier dans la modale
            //$result = $cartService->add($id);
            //$total = array_sum($result);
            $panierWithData = $cartService->getFullCart();
            if (!empty($panierWithData)) {
                $quantityProducts = $cartService->getQuantity();
                $total = array_sum($panierWithData);
                $modalProducts = $panierWithData;
                /*$quantityProducts = array_sum($panier);
                $modalProducts = [];
                foreach ($panier as $id => $quantity) {
                    $modalProducts[] = [
                        'product' => $productRepository->find($id),
                        'quantity' => $quantity
                    ];
                }
                $total = 0;
                foreach ($modalProducts as $product) {
                    $totalProduct = $product['product']->getPrice() * $product['quantity'];
                    $total += $totalProduct;
                }*/
            } else {
                $quantityProducts = '';
                $modalProducts = '';
                $total = '';
            }
            return $this->render('user/partials/_cart-modal-body.html.twig',[
                'quantityProducts' => $quantityProducts,
                'total' => $total,
                'modalProducts' => $modalProducts
            ]);
        }
    }
}

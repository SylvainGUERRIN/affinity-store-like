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
            $panierWithData = $cartService->getFullCart();
            if (!empty($panierWithData)) {
                $quantityProducts = $cartService->getQuantity();
                $total = $cartService->getTotalPrice();
                $modalProducts = $panierWithData;
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

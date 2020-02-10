<?php


namespace App\Controller;


use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PayController extends AbstractController
{
    /**
     * @Route("/order", name="index_order")
     * @param CartService $cartService
     * @return Response
     */
    public function index(CartService $cartService)
    {
        /*$panier = $cartService->getFullCart();
        dd($panier);*/
        return $this->render('site/paiement/index.html.twig');
    }
}

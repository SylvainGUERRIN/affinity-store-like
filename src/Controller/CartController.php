<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @return Response
     * @Route("/panier", name="cart")
     */
    public function cart()
    {
        return $this->render('site/boutique/cart.html.twig');
    }

}

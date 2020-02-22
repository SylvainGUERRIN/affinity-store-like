<?php


namespace App\Controller;


use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PayController extends AbstractController
{
    /**
     * @Route("/paiement", name="pay_index")
     * @param CartService $cartService
     * @return Response
     */
    public function index(CartService $cartService): Response
    {
        $panier = $cartService->getFullCart();
        dd($panier);

        foreach ($panier as $item) {
            //ça marche avec cart mais pas avec order (mot réservé par mysql)
            //changer dans la bdd le mot order de la table
            //une fois ok, faire le order
//            $order = new Order();
//            $order->setUser($user);
//            $order->setProductName($item['product']->getName());
//            //reference is a reserved word in mysql, change it and retry
//            //i change it but it's not enough
//            $order->setRef('jdfdsuihf');
//            $order->setPrice($item['product']->getPrice());
//            $order->setQuantity($item['quantity']);
//            $order->setAmount($item['product']->getPrice() * $item['quantity']);
//            $order->setCreatedAt(new \DateTime('now'));

//            dd($order);
        }

        //envoyer le systéme de paiement avec stripe grâce à http component
        //une fois le paiement validé supprimer les carts correspondants

        return $this->render('site/paiement/index.html.twig');
    }
}

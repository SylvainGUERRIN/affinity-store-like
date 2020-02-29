<?php


namespace App\Controller;


use App\Entity\Paiement;
use App\Entity\User;
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
     * @throws \Exception
     */
    public function index(CartService $cartService): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $panier = $cartService->getFullCart();
//        dd($panier);

//        systéme de paiement, le laisser sans les clés api

        //envoyer les orders après si le paiement est ok
        foreach ($panier as $item) {
            $order = new Paiement();
            $order->setUser($user);
            $order->setProductName($item['product']->getName());
            //reference is a reserved word in mysql, change it and retry
            //i change it but it's not enough
            $order->setRef('jdfdsuihf');
            $order->setPrice($item['product']->getPrice());
            $order->setQuantity($item['quantity']);
            $order->setAmount($item['product']->getPrice() * $item['quantity']);
            $order->setCreatedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
//            dd($order);
        }

        //envoyer le systéme de paiement avec stripe grâce à http component
        //une fois le paiement validé supprimer les carts correspondants

        return $this->render('site/paiement/index.html.twig');
    }
}

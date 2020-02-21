<?php


namespace App\Controller;


use App\Entity\Order;
use App\Entity\User;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="index_order")
     * @param CartService $cartService
     * @return Response
     * @throws \Exception
     */
    public function index(CartService $cartService)
    {
        /** @var User $user */
        $user = $this->getUser();

        if (is_object($user) === false) {
//            dd(is_object($user));
            $this->addFlash('danger',
                "Vous devez être connecter pour finaliser votre paiement !"
            );
            return $this->redirectToRoute('user_connexion');
        }
        $panier = $cartService->getFullCart();
//        dd($panier);
        //put condition si order already exists before create order
        $item= [];
        foreach ($panier as $item){
            $order = new Order();
            $order->setCreatedAt(new \DateTime('now'));
            $order->setUser($user);
            $order->setProductName($item['product']->getName());
            $order->setPrice($item['product']->getPrice());
            $order->setQuantity($item['quantity']);
            $order->setAmount($item['product']->getPrice() * $item['quantity']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
        }
//        dd($order);

        return $this->render('site/order/index.html.twig');
    }
}

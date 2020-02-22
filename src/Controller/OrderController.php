<?php


namespace App\Controller;


use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="index_order")
     * @param CartService $cartService
     * @param CartRepository $cartRepository
     * @return Response
     * @throws \Exception
     */
    public function index(CartService $cartService, CartRepository $cartRepository): Response
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

        $item= [];
        foreach ($panier as $item){
            $productName = $item['product']->getName();
            $verifCartAlreadyExist = $cartRepository->findByUserAndProduct($productName, $user);
            if($verifCartAlreadyExist !== null){
                $verifCartAlreadyExist->setPrice($item['product']->getPrice());
                $verifCartAlreadyExist->setQuantity($item['quantity']);
                $verifCartAlreadyExist->setAmount($item['product']->getPrice() * $item['quantity']);
                $verifCartAlreadyExist->setUpdatedAt(new \DateTime('now'));

                $em = $this->getDoctrine()->getManager();
                $em->persist($verifCartAlreadyExist);
                $em->flush();
            }elseif ($verifCartAlreadyExist === null){
                $newCart = new Cart();
                $newCart->setUser($user);
                $newCart->setProductName($productName);
                //reference is a reserved word in mysql, change it and retry
                //i change it but it's not enough
                $newCart->setPrice($item['product']->getPrice());
                $newCart->setQuantity($item['quantity']);
                $newCart->setAmount($item['product']->getPrice() * $item['quantity']);
                $newCart->setCreatedAt(new \DateTime('now'));

                $em = $this->getDoctrine()->getManager();
                $em->persist($newCart);
                $em->flush();
            }

        }
//        dd($order);

        return $this->render('site/order/index.html.twig');
    }
}

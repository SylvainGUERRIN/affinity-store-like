<?php


namespace App\Controller;


use App\Entity\Cart;
use App\Entity\DeliveryAddress;
use App\Entity\User;
use App\Form\DeliveryAddressType;
use App\Form\DeliveryDetailsType;
use App\Repository\CartRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="index_order")
     * @param CartService $cartService
     * @param CartRepository $cartRepository
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(
        CartService $cartService,
        CartRepository $cartRepository,
        Request $request
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (is_object($user) === false) {
            $this->addFlash('danger',
                "Vous devez être connecté pour finaliser votre paiement !"
            );
            return $this->redirectToRoute('user_connexion');
        }
        $panier = $cartService->getFullCart();
        $em = $this->getDoctrine()->getManager();
        $ancientProducts = $cartRepository->findByUserID($user);
        foreach($ancientProducts as $ancientProduct){
            $em->remove((object)$ancientProduct);
            $em->flush();
        }

        $item= [];
        foreach ($panier as $item){
            $productName = $item['product']->getName();

            /*$verifCartAlreadyExist = $cartRepository->findByUserAndProduct($productName, $user);
            if($verifCartAlreadyExist !== null){
                /*$em->remove($verifCartAlreadyExist);
                $em->flush();*/
                //comparaison avec le cart en bdd pour enlever les produits qui ne sont plus dans le cart de la session
                /*$verifCartAlreadyExist->setPrice($item['product']->getPrice());
                $verifCartAlreadyExist->setQuantity($item['quantity']);
                $verifCartAlreadyExist->setAmount($item['product']->getPrice() * $item['quantity']);
                $verifCartAlreadyExist->setUpdatedAt(new \DateTime('now'));

                $em->persist($verifCartAlreadyExist);
                $em->flush();
            }elseif ($verifCartAlreadyExist === null){*/
                $newCart = new Cart();
                $newCart->setUser($user);
                $newCart->setProductName($productName);
                //reference is a reserved word in mysql, change it and retry
                //i change it but it's not enough
                $newCart->setPrice($item['product']->getPrice());
                $newCart->setQuantity($item['quantity']);
                $newCart->setAmount($item['product']->getPrice() * $item['quantity']);
                $newCart->setCreatedAt(new \DateTime('now'));

                $em->persist($newCart);
                $em->flush();
            //}

        }
//        dd($order);
        $dateNow = new \DateTime('now');
        $deliveryDate = $dateNow->modify('+2 days');

        $form = $this->createForm(DeliveryDetailsType::class);
        $deliveryAddress = new DeliveryAddress();
        $formDeliveryAddress = $this->createForm(DeliveryAddressType::class, $deliveryAddress);

        $formDeliveryAddress->handleRequest($request);
        if($formDeliveryAddress->isSubmitted() && $formDeliveryAddress->isValid()){
            //$deliveryAddress = new DeliveryAddress();
            $em = $this->getDoctrine()->getManager();
            $deliveryAddress->setUser($user);
            $em->persist($deliveryAddress);
            $em->flush();
        }

        return $this->render('site/order/index.html.twig',[
            'userStreet' => $user->getAddress(),
            'userCP' => $user->getCp(),
            'userTown' => $user->getTown(),
            'deliveryDate' => $deliveryDate,
            'formDeliveryAddress' => $formDeliveryAddress->createView()
        ]);
    }
}

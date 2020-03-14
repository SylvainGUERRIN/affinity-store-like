<?php


namespace App\Controller;


use App\Entity\Paiement;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PayController extends AbstractController
{
    /**
     * @Route("/paiement", name="pay_index")
     * @param CartService $cartService
     * @param SessionInterface $session
     * @param Request $request
     * @return Response
     * @throws ApiErrorException
     * @IsGranted("ROLE_USER")
     */
    public function index(CartService $cartService, SessionInterface $session, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
//        dd($user->getMail());

//        $panier = $cartService->getFullCart();
//        dd($panier);

        //envoyer le systéme de paiement avec stripe grâce à http component
        //une fois le paiement validé supprimer les carts correspondants
//        $address = $session->get('command-address');
//        if ($request->request->get('stripeToken')) {
            //création du paiement
            // Set your secret key: remember to change this to your live secret key in production
            // See your keys here: https://dashboard.stripe.com/account/apikeys
//            \Stripe\Stripe::setApiKey('sk_test');
//            dd($_ENV['STRIPE_SECRET_KEY']);
            \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

            // Token is created using Checkout or Elements!
            // Get the payment token ID submitted by the form:
            $token = $request->request->get('stripeToken');

            //try payment intend
//            $intent = \Stripe\PaymentIntent::create([
//                'amount' => $cartService->getTotalPrice() * 100,
//                'currency' => 'eur',
//                'payment_method_types' => ['card'],
//            ]);

            //try to dd for look to response api
//            dd($intent->toArray());

            $create = \Stripe\Checkout\Session::create([
                'customer_email' => $user->getMail(),
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'name' => 'T-shirt',
                    'description' => 'Comfortable cotton t-shirt',
                    'images' => ['https://example.com/t-shirt.png'],
                    'amount' => $cartService->getTotalPrice() * 100,
                    'currency' => 'eur',
                    'quantity' => 1
                ]],
//                'payment_intent_data' => [
//                    'capture_method' => 'manual',
//                ],
                'success_url' => 'https://example.com/success',
                'cancel_url' => 'https://example.com/cancel',
            ]);

//            pour retrouver une session du create, enlever le cs-test et mettre celui qui correspond au produit
//            pour la récupérer il faut capturer la variable CHECKOUT_SESSION_ID
//            $id = \Stripe\Checkout\Session::retrieve('cs_test_0aYsFck6Ya1aWsvsiyAb8v4FkE9MZxH0cgP8FhbNOLnlx4j8fsKHqEYY');


            //la récupération de la charge marche
//            dd($create);

            //just for charge payment
            /*$charge = \Stripe\Charge::create([
                'amount' => $cartService->getTotalPrice() * 100,
                'currency' => 'eur',
                'description' => 'Example charge',
                'source' => $token,
            ]);
            if ($charge->status === 'succeeded') {
                return $this->redirectToRoute('command_process');
            }*/

            //pour récupérer l'id de la session et l'envoyer au js du template
            $CHECKOUT_SESSION_ID = $create->id;
//            dd($CHECKOUT_SESSION_ID);

//        }
        return $this->render('site/command/payment.html.twig', [
            'total' => $cartService->getTotalPrice(),
//            'address' => $address,
            'CHECKOUT_SESSION_ID' => $CHECKOUT_SESSION_ID
//            'pk_test' => \Stripe\Stripe::setApiKey(getenv('STRIPE_PUBLISHABLE_KEY'))
        ]);

    }

    /**
     * @Route("/process", name="command_process")
     *
     * @param SessionInterface $session
     * @param CartService $cartService
     * @param ProductRepository $repo
     * @param Security $security
     * @return Response
     * @throws \Exception
     */
    public function process(
        SessionInterface $session,
        CartService $cartService,
        ProductRepository $repo,
        Security $security
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $panier = $cartService->getFullCart();

        foreach ($panier as $item) {
            $order = new Paiement();
            $order->setUser($user);
            $order->setProductName($item['product']->getName());
            $order->setRef('jdfdsuihf');
            $order->setPrice($item['product']->getPrice());
            $order->setQuantity($item['quantity']);
            $order->setAmount($item['product']->getPrice() * $item['quantity']);
            $order->setCreatedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
        }

        $this->addFlash("success", "Vous serez livré à " . $user->getAddress());
        //vide le panier qui est dans la session
        $cartService->empty();

        return $this->render('site/command/success.html.twig',[
            'quantityProducts' => ''
        ]);
    }
}

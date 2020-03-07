<?php


namespace App\Controller;


use App\Entity\Paiement;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Stripe\Exception\ApiErrorException;
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
     */
    public function index(CartService $cartService, SessionInterface $session, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $panier = $cartService->getFullCart();
//        dd($panier);

        //envoyer le systéme de paiement avec stripe grâce à http component
        //une fois le paiement validé supprimer les carts correspondants
        $address = $session->get('command-address');
        if ($request->request->get('stripeToken')) {
            //création du paiement
            // Set your secret key: remember to change this to your live secret key in production
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            \Stripe\Stripe::setApiKey('sk_test');

            // Token is created using Checkout or Elements!
            // Get the payment token ID submitted by the form:
            $token = $request->request->get('stripeToken');
            $charge = \Stripe\Charge::create([
                'amount' => $cartService->getTotalPrice() * 100,
                'currency' => 'eur',
                'description' => 'Example charge',
                'source' => $token,
            ]);
            if ($charge->status === 'succeeded') {
                return $this->redirectToRoute('command_process');
            }
        }
        return $this->render('site/command/payment.html.twig', [
            'total' => $cartService->getTotalPrice(),
            'address' => $address
        ]);

    }

    /**
     * @Route("/process", name="command_process")
     *
     * @param ObjectManager $manager
     * @param SessionInterface $session
     * @param CartService $cartService
     * @param ProductRepository $repo
     * @param Security $security
     * @return Response
     * @throws \Exception
     */
    public function process(
        ObjectManager $manager,
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

        return $this->render('site/command/success.html.twig');
    }
}

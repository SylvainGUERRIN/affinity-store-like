<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PaymentsController
 * @package App\Controller\Admin
 * @Route("compo-admin/administration")
 */
class PaymentsController extends AbstractController
{
    /**
     * index of payments to charge later
     * @Route("/payments-to-charge-later")
     */
    public function charges()
    {
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
        \Stripe\PaymentMethod::all([
            'customer' => '{{CUSTOMER_ID}}',
            'type' => 'card',
        ]);

        $this->render('admin/payments/index.html.twig');
    }
}

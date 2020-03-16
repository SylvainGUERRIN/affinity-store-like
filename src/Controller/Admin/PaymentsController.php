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

        $customers = \Stripe\Customer::all(['limit' => 3]);
        $customers->toArray();

//        $retrieve =\Stripe\Checkout\Session::retrieve('cs_test_0aYsFck6Ya1aWsvsiyAb8v4FkE9MZxH0cgP8FhbNOLnlx4j8fsKHqEYY');
//        dd($customers->data);
//        dd($retrieve);

        //before that how to capture charge id ? la récupérer
        //to capture a charge
        $charge = \Stripe\Charge::retrieve(
            'ch_1FUXzkFCcaS3ZpNJTkC87BRn'
        );
        dd($charge);
        $charge->capture();

        // customer id for example cus_Gukzdjy4FjIh28
        $allPaymentsToCharge = \Stripe\PaymentMethod::all([
            'customer' => 'cus_Gukzdjy4FjIh28',
            'type' => 'card',
        ]);

        dd($allPaymentsToCharge->toArray());

        $this->render('admin/payments/index.html.twig');
    }
}

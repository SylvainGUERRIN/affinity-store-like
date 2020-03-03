<?php


namespace App\Controller;


use App\Entity\PaiementMethod;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PayAjaxController extends AbstractController
{
    /**
     * fonction en ajax pour ajouter une méthode de paiement
     * @Route("/ajax/add/paiement-method", name="add_paiement_method")
     * @param Request $request
     * @param CartService $cartService
     */
    public function addPayMethod(Request $request, CartService $cartService): void
    {
        if($request->isXmlHttpRequest()) {

            //mettre une instance du paiement
            //donc préparer l'entité paiement
            $paiement = null;

            $value = $request->get('value');
            $em = $this->getDoctrine()->getManager();

            if($value === 'CB'){
                $method = 'carte bleue';
            }
            if($value === 'CHEQ'){
                $method = 'chéque';
            }
            $payMethod = new PaiementMethod();
            $payMethod->setPaiement($paiement);
            $payMethod->setName($method);

            $em->persist($payMethod);
            $em->flush();
//            return $this->render('user/partials/_cart-modal-body.html.twig',[
//                'quantityProducts' => $quantityProducts,
//                'total' => $total,
//                'modalProducts' => $modalProducts
//            ]);
        }
    }
}

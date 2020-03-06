<?php


namespace App\Controller;


use App\Entity\PaiementMethod;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @return JsonResponse
     */
    public function addPayMethod(Request $request, CartService $cartService)
    {
        if($request->isXmlHttpRequest()) {

            /** @var $user */
            $user = $this->getUser();

            $value = $request->get('value');
            $em = $this->getDoctrine()->getManager();

            if($value === 'CB'){
                $method = 'carte bleue';
            }
            if($value === 'CHEQ'){
                $method = 'chéque';
            }
            $payMethod = new PaiementMethod();
            $payMethod->setUser($user);
            $payMethod->setName($method);

            $em->persist($payMethod);
            $em->flush();
            return new JsonResponse($data = 'confirm');
        }
    }
}

<?php


namespace App\Controller;


use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @param SessionInterface $session
     * @param ProductRepository $productRepository
     * @return Response
     * @Route("/panier", name="cart")
     */
    public function cart(SessionInterface $session, ProductRepository $productRepository)
    {
        $panier = $session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $id => $quantity){
            $panierWithData[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;
        $quantityProducts = 0;
        foreach ($panierWithData as $item){
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
            $quantityProducts += $item['quantity'];
        }

        return $this->render('site/boutique/cart.html.twig',[
            'quantityProducts' => $quantityProducts,
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

    /**
     * @route("/panier/add/{id}", name="cart_add")
     * @param $id
     * @param SessionInterface $session
     * @param Request $request
     * @return Response
     */
    public function add($id, SessionInterface $session, Request $request): Response
    {
        if($request->isXmlHttpRequest()){
            $panier = $session->get('panier', []);
            if(!empty($panier[$id])){
                $panier[$id]++;
                $session->set('panier', $panier);
                $total = array_sum($panier);
            }else{
                $panier[$id] = 1;
                $session->set('panier', $panier);
                $total = array_sum($panier);
            }
            return new JsonResponse($total);
        }
//        return $this->render('user/partials/_cart.html.twig', [
//            'number' => $total,
//        ]);
    }

    /**
     * @route("/panier/remove/{id}", name="cart_remove")
     * @param $id
     * @param SessionInterface $session
     * @param Request $request
     * @return Response
     */
    public function remove($id, SessionInterface $session, Request $request): Response
    {
        $cart = $session->get('panier', []);

        if($request->isXmlHttpRequest()){
            $panier = $session->get('panier', []);
            if(!empty($panier[$id])){
                $panier[$id]--;
                $session->set('panier', $panier);
                $total = array_sum($panier);
            }else{
                $panier[$id] = 1;
                $session->remove($panier[$id]);
                $total = array_sum($panier);
            }
        }
        return $this->render('user/partials/_cart.html.twig', [
            'number' => $total,
        ]);
    }
}

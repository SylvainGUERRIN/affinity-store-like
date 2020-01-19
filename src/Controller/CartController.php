<?php


namespace App\Controller;


use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        foreach ($panierWithData as $item){
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        return $this->render('site/boutique/cart.html.twig',[
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

    /**
     * @route("/panier/add/{id}", name="cart_add")
     * @param $id
     * @param SessionInterface $session
     * @return Response
     */
    public function add($id, SessionInterface $session): Response
    {
        if($request->isXmlHttpRequest()){
            $cart = $session->get('panier', []);
            dd($panier);

            if(!empty($panier[$id])){
                $panier[$id]++;
                return $this->render('user/partials/_cart.html.twig', [
                    'number' => $number,
                ]);
            }else{
                $panier[$id] = 1;
                return $this->render('user/partials/_cart.html.twig', [
                    'number' => $number,
                ]);
            }
        }
    }

    /**
     * @route("/panier/remove/{id}", name="cart_remove")
     * @param $id
     * @param SessionInterface $session
     */
    public function remove($id, SessionInterface $session): void
    {
        $cart = $session->get('panier', []);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier', $panier);
    }
}

<?php


namespace App\Controller;


use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class cartAjaxController extends AbstractController
{
    /**
     * fonction en ajax pour ajouter un article dans la fenetre modale
     * @Route("/ajax/article/add-cart", name="article_add_cart")
     * @param Request $request
     * @param SessionInterface $session
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function articleAddCart(Request $request, SessionInterface $session, ProductRepository $productRepository): Response
    {
        if($request->isXmlHttpRequest()) {
            $panier = $session->get('panier', []);
//            dd($panier);
            if (!empty($panier)) {
                $quantityProducts = array_sum($panier);
                $modalProducts = [];
                foreach ($panier as $id => $quantity) {
                    $modalProducts[] = [
                        'product' => $productRepository->find($id),
                        'quantity' => $quantity
                    ];
                }
                $total = 0;
                foreach ($modalProducts as $product) {
                    $totalProduct = $product['product']->getPrice() * $product['quantity'];
                    $total += $totalProduct;
                }
            } else {
                $quantityProducts = '';
                $modalProducts = '';
                $total = '';
            }
            return $this->render('user/partials/_cart-modal-body.html.twig',[
                'quantityProducts' => $quantityProducts,
                'total' => $total,
                'modalProducts' => $modalProducts
            ]);
        }
    }
}

<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminAccountController
 * @package App\Controller
 */
class SiteController extends AbstractController
{
    /**
     * @param SessionInterface $session
     * @return Response
     * @Route("/", name="home")
     */
    public function home(SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        if (!empty($panier)) {
            $quantityProducts = array_sum($panier);
        } else {
            $quantityProducts = '';
        }
        return $this->render('site/home.html.twig', [
            'quantityProducts' => $quantityProducts,
        ]);
    }

    /**
     * @param ProductRepository $productRepository
     * @param SessionInterface $session
     * @return Response
     * @Route("/boutique", name="boutique")
     */
    public function boutique(ProductRepository $productRepository, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
//        dd($panier);
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
        $allProducts = $productRepository->findAll();
        return $this->render('site/boutique/index.html.twig', [
            'products' => $allProducts,
            'quantityProducts' => $quantityProducts,
            'modalProducts' => $modalProducts,
            'total' => $total
        ]);
    }

    /**
     * @param SessionInterface $session
     * @return Response
     * @Route("/contact", name="contact")
     */
    public function contact(SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        if (!empty($panier)) {
            $quantityProducts = array_sum($panier);
        } else {
            $quantityProducts = '';
        }
        return $this->render('site/contact.html.twig', [
            'quantityProducts' => $quantityProducts,
        ]);
    }
}

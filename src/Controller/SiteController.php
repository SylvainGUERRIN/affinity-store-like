<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\Cart\CartService;
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
     * @param CartService $cartService
     * @return Response
     * @Route("/", name="home")
     */
    public function home(CartService $cartService): Response
    {
        return $this->render('site/home.html.twig', [
            'quantityProducts' => $cartService->getQuantity(),
        ]);
    }

    /**
     * @param ProductRepository $productRepository
     * @param SessionInterface $session
     * @param CartService $cartService
     * @return Response
     * @Route("/boutique", name="boutique")
     */
    public function boutique(ProductRepository $productRepository, SessionInterface $session, CartService $cartService): Response
    {
        return $this->render('site/boutique/index.html.twig', [
            'products' => $productRepository->findAll(),
            'quantityProducts' => $cartService->getQuantity(),
            'modalProducts' => $cartService->getFullCart(),
            'total' => $cartService->getTotalPrice()
        ]);
    }

    /**
     * @param CartService $cartService
     * @return Response
     * @Route("/contact", name="contact")
     */
    public function contact(CartService $cartService): Response
    {
        return $this->render('site/contact.html.twig', [
            'quantityProducts' => $cartService->getQuantity(),
        ]);
    }
}

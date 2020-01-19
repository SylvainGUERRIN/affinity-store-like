<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminAccountController
 * @package App\Controller
 */
class SiteController extends AbstractController
{
    /**
     * @return Response
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('site/home.html.twig');
    }

    /**
     * @param ProductRepository $productRepository
     * @return Response
     * @Route("/boutique", name="boutique")
     */
    public function boutique(ProductRepository $productRepository)
    {
        $allProducts = $productRepository->findAll();
        return $this->render('site/boutique/index.html.twig', [
            'products' => $allProducts
        ]);
    }

    /**
     * @return Response
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('site/contact.html.twig');
    }
}

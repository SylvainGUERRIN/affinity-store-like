<?php

namespace App\Controller;

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
}

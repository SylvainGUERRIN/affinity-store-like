<?php

namespace App\Service\Cart;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param int $id
     */
    public function add(int $id): void
    {
        $panier = $this->session->get('panier', []);
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        $this->session->set('panier', $panier);
    }

    /**
     * @param int $id
     */
    public function remove(int $id): void
    {

    }

    public function getFullCart(): array
    {

    }

    public function getTotal(): float
    {

    }
}

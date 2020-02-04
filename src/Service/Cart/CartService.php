<?php

namespace App\Service\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
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
    public function less(int $id): void
    {
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id])) {
            if ($panier[$id] === 1) {
                unset($panier[$id]);
            }else{
                $panier[$id]--;
            }
            $this->session->set('panier', $panier);
        }

    }

    /**
     * @param int $id
     */
    public function remove(int $id): void
    {
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $this->session->set('panier', $panier);

    }

    public function getFullCart(): array
    {
        $panier = $this->session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $id => $quantity){
            $panierWithData[] = [
                'product' => $this->productRepository->find($id),
                'quantity' => $quantity
            ];
        }
    }

    public function getTotal(): float
    {

    }
}

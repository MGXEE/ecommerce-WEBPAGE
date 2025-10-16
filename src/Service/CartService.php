<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService 
{
    public function __construct(private SessionInterface $session) {}
    public function clear(): void
    {
        $this->session->remove('cart');
        
    }
}
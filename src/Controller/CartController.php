<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Cast\Int_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart/add/{id}', name: 'cart_add', methods: ['GET'])]
    public function addToCart(Products $product, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        //find open cart for this user
        $cart = $em->getRepository(Cart::class)->findOneBy([
            'user' => $user,
            'status' => 'OPEN'
        ]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $cart->setStatus('OPEN');
            $em->persist($cart);
        }
        //check if product is already in cart
        $cartItem = $em->getRepository(CartItem::class)->findOneBy([
            'cart' => $cart,
            'product' => $product
        ]);

        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        } else {
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);
            $em->persist($cartItem);
        }

        $em->flush();

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart', name: 'cart_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

    
        $cart = $em->getRepository(Cart::class)->findOneBy([
            'user' => $user,
            'status' => 'OPEN'
        ]);

        $cartItems = $cart ? $cart->getItems() : [];
        $total = 0.0;

        if ($cart) {
            foreach ($cartItems as $item )
                // $total += (float) $item->getProduct()->getPrice() * (int) $item->getQuantity();
            $total += (float) $item->getProduct()->getPriceWithDiscount() * (int) $item->getQuantity();
        }
        
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    #[Route('/cart/remove{id}', name: 'cart_remove', methods: ['GET'])]
    public function removeItem( int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $cartItem = $em->getRepository(CartItem::class)->find($id);
        if ($cartItem && $cartItem->getCart()->getUser() === $user) {
            $em->remove($cartItem);
            $em->flush();
        }

        return $this->redirectToRoute('cart_index');

    }

    #[Route('/cart/increase{id}', name: 'cart_increase', methods: ['GET'])]
    public function increaseQuantity( int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $cartItem = $em->getRepository(CartItem::class)->find($id);

        if ($cartItem && $cartItem->getCart()->getUser() === $user) {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
            $em->flush();
        }

        return $this->redirectToRoute('cart_index');

    }

    #[Route('/cart/decrease{id}', name: 'cart_decrease', methods: ['GET'])]
    public function decreaseQuantity( int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $cartItem = $em->getRepository(CartItem::class)->find($id);


        if ($cartItem && $cartItem->getCart()->getUser() === $user) {
            if  ($cartItem->getQuantity() > 1 ) {
                $cartItem->setQuantity($cartItem->getQuantity() - 1);
                $em->flush();
            }

        } else{
            $em->remove($cartItem);
            $em->flush();
        }

            

        return $this->redirectToRoute('cart_index');
        

    }


    #test for admin statistic page
    #[Route('/cart/test', name: 'cart_test', methods: ['GET'])]
public function testCart(EntityManagerInterface $em): Response
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    $user = $this->getUser();

    $cart = $em->getRepository(Cart::class)->findOneBy([
        'user' => $user,
        'status' => 'OPEN'
    ]);

    $cartItems = $cart ? $cart->getItems() : [];
    $total = 0.0;

    foreach ($cartItems as $item) {
        // $total += (float) $item->getProduct()->getPrice() * (int) $item->getQuantity();
        $total += (float) $item->getProduct()->getPriceWithDiscount() * (int) $item->getQuantity();
    }

    return $this->render('cart/test.html.twig', [
        'cartItems' => $cartItems,
        'total' => $total
    ]);
}

    // btn checkout test
    #[Route('/cart/checkout', name: 'cart_checkout', methods: ['POST'])]
public function checkout(): Response
{
    return new Response('Checkout clicked!');
}

}

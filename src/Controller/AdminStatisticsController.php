<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\CartRepository;
use App\Repository\CartItemRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStatisticsController extends AbstractController
{
    #[Route('/admin/statistics', name: 'admin_statistics')]
    public function index(
        CartRepository $cartRepository,
        CartItemRepository $cartItemRepository,
        ProductsRepository $productsRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $completedCarts = $cartRepository->findBy(['status' => 'COMPLETED']);

        $totalRevenue = 0;
        $productSales = [];

        foreach ($completedCarts as $cart) {
            foreach ($cart->getItems() as $item) {
                $productId = $item->getProduct()->getId();
                $amount = $item->getQuantity() * $item->getProduct()->getPrice();
                $totalRevenue += $amount;

                if (!isset($productSales[$productId])) {
                    $productSales[$productId] = [
                        'product' => $item->getProduct()->getProduct(),
                        'category' => $item->getProduct()->getCategory(),
                        'quantity' => 0,
                        'revenue' => 0,
                    ];
                }

                $productSales[$productId]['quantity'] += $item->getQuantity();
                $productSales[$productId]['revenue'] += $amount;
            }
        }

        return $this->render('admin/statistics.html.twig', [
            'totalRevenue' => $totalRevenue,
            'productSales' => $productSales,
        ]);
    }
}

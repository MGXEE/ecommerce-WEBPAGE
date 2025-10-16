<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
use App\Form\CheckoutType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

final class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_checkout')]
    public function checkout(Request $request, EntityManagerInterface $em): Response
    {

        $order = new Order();

        $form = $this->createForm(CheckoutType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart = $em->getRepository(Cart::class)->findOneBy([
                'user' => $this->getUser(),
                'status' => 'OPEN' // <— very important
            ]);

            $total = 0;
            $itemsForOrder = [];

            if ($cart) {
                foreach ($cart->getItems() as $item) {
                    $product = $item->getProduct();
                    $quantity = $item->getQuantity();
                    $price = $product->getPrice();
                    $line = $quantity * $price;

                    $itemsForOrder[] = [
                        'product' => $product->getProduct(),
                        'price' => $price,
                        'quantity' => $quantity,
                        'line_total' => $line,
                    ];
                    $total += $line;
                }
            }

            $order->setUser($this->getUser());
            $order->setItems($itemsForOrder);
            $order->setTotal($total);
            $order->setStatus('paid');
            $order->setPaymentMethod($form->get('paymentMethod')->getData());
            $order->setPaidAt(new \DateTime());
            $order->setCreatedAt(new \DateTimeImmutable());

            $em->persist($order);

            // ✅ Close the cart after order
            if ($cart) {
                $cart->setStatus('PAID');
                foreach ($cart->getItems() as $item) {
                    $em->remove($item); // optional: remove items
                }
                $em->flush();
            }

            // Redirect to the success page
            return $this->redirectToRoute('app_order_success', ['id' => $order->getId()]);
        }


        // Render the checkout form if not submitted or invalid
        return $this->render('checkout/checkout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/order/success/{id}', name: 'app_order_success')]
    public function success(Order $order): Response
    {
        return $this->render('checkout/success.html.twig', [
            'order' => $order
        ]);
    }



    #[Route('/order/download/{id}', name: 'app_order_download')]
    public function downloadInvoice(Order $order): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // render HTML from twig
        $html = $this->renderView('checkout/invoice.html.twig', [
            'order' => $order
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="invoice-' . $order->getId() . '.pdf"',
            ]
        );
    }
}

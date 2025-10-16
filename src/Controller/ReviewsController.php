<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReviewsController extends AbstractController
{
    #[Route('/product/{id}/reviews', name: 'product_reviews')]
    public function reviews(Products $product, Request $request, EntityManagerInterface $em): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setProduct($product);
            $review->setCreatedAt(new \DateTime());
            $em->persist($review);
            $em->flush();

            $this->addFlash('success', 'Review submitted!');
            return $this->redirectToRoute('product_reviews', ['id' => $product->getId()]);
        }

        return $this->render('reviews/index.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'reviews' => $product->getReviews(),
        ]);
    }
    #[Route('/review/{id}/delete', name: 'review_delete', methods: ['POST'])]
    public function deleteReview(Review $review, EntityManagerInterface $em): Response
    {
        // Prüfen, ob der User Admin ist
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Nur Admins können Reviews löschen.');
        }

        $em->remove($review);
        $em->flush();

        $this->addFlash('success', 'Review wurde gelöscht.');

        // Weiterleitung zurück zur Produktseite (angenommen, Review hat eine Produkt-Relation)
        return $this->redirectToRoute('product_reviews', ['id' => $review->getProduct()->getId()]);
    }
}

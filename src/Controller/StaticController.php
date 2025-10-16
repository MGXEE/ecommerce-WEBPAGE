<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;   // <-- hinzufügen
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class StaticController extends AbstractController
{
    #[Route('/', name: 'app_static')]
    public function index(Request $request, ProductsRepository $productsRepository): Response
    {
        $filter = $request->query->get('filter', 'all');  // 'all' default
        $searchTerm = $request->query->get('q');


        if ($filter === 'product' && $searchTerm) {
            // Suche nur nach sichtbaren Produkten, die den Suchbegriff enthalten
            $products = $productsRepository->createQueryBuilder('p')
                ->where('p.product LIKE :search')
                ->andWhere('p.visibility = :visible')
                ->setParameter('search', '%' . $searchTerm . '%')
                ->setParameter('visible', true)
                ->getQuery()
                ->getResult();
        } else {
            // Alle sichtbaren Produkte anzeigen
            $products = $productsRepository->createQueryBuilder('p')
                ->where('p.visibility = :visible')
                ->setParameter('visible', true)
                ->getQuery()
                ->getResult();
        }

        return $this->render('static/index.html.twig', [
            'products' => $products,
            'filter' => $filter,
            'searchTerm' => $searchTerm,
        ]);
    }

    #[Route('/about', name: 'app_static_about')]
    public function about(): Response
    {
        return $this->render('static/about.html.twig', [
            'page_title' => 'About Us',
        ]);
    }
}

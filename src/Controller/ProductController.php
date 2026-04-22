<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index')]
    public function index(
        ProductRepository $productRepo,
        CategoryRepository $categoryRepo,
        Request $request,
        PaginatorInterface $paginator
    ): Response
    {
        $categoryId = $request->query->get('category');
        $categories = $categoryRepo->findAll();

        // QUERY BUILDER (instead of findAll / findBy)
        $qb = $productRepo->createQueryBuilder('p');

        // FILTER CATEGORY
        if ($categoryId) {
            $qb->where('p.category = :cat')
               ->setParameter('cat', $categoryId);
        }

        $qb->orderBy('p.id', 'DESC');

        // PAGINATION
        $products = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
        ]);
    }

    // VIEW PRODUCT DETAIL
    #[Route('/product/{id}', name: 'product_show')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
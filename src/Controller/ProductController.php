<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductServiceClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index')]
    public function index(Request $request, ProductServiceClient $productServiceClient): Response
    {
        $data = $productServiceClient->getProductsWithFilter($request);

        return $this->render('product/index.html.twig', $data);
    }

    #[Route('/product/{id}', name: 'product_show')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
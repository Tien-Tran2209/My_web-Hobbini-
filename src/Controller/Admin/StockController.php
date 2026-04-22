<?php

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class StockController extends AbstractController
{
    #[Route('/admin/product/stock', name: 'admin_product_stock')]
    public function index(ProductRepository $productRepo): Response
    {
        $products = $productRepo->findAll();

        $stats = [];

        foreach ($products as $product) {

            $stats[] = [
                'product' => $product,
                'sold' => $product->getSold(),
                'remaining' => $product->getRemaining()
            ];
        }

        return $this->render('admin/product/stock.html.twig', [
            'stats' => $stats
        ]);
    }

    #[Route('/admin/product/{id}/stock/update', name: 'admin_product_stock_update', methods: ['POST'])]
    public function updateStock(
        int $id,
        ProductRepository $productRepo,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product = $productRepo->find($id);

        if (!$product) {
            throw $this->createNotFoundException();
        }

        $newStock = (int) $request->request->get('stock');

        if ($newStock < 0) {
            $newStock = 0;
        }

        $product->setStock($newStock);

        $em->flush();

        return $this->redirectToRoute('admin_product_stock');
    }
}
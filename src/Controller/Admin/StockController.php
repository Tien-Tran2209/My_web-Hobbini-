<?php

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use App\Service\StockService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    #[Route('/admin/product/stock', name: 'admin_product_stock')]
    public function index(
        ProductRepository $productRepo,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $query = $productRepo->createQueryBuilder('p')->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $stats = [];

        foreach ($pagination as $product) {
            $stats[] = [
                'product' => $product,
                'sold' => $product->getSold(),
                'remaining' => $product->getRemaining(),
            ];
        }

        return $this->render('admin/product/stock.html.twig', [
            'stats' => $stats,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/admin/product/{id}/stock/update', name: 'admin_product_stock_update', methods: ['POST'])]
    public function updateStock(
        int $id,
        ProductRepository $productRepo,
        Request $request,
        StockService $stockService
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product = $productRepo->find($id);

        if (!$product) {
            throw $this->createNotFoundException();
        }

        $newStock = (int) $request->request->get('stock');

        $stockService->update($product, $newStock);

        $this->addFlash('success', 'Stock mis à jour avec succès.');

        return $this->redirectToRoute('admin_product_stock');
    }
}
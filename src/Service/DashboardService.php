<?php

namespace App\Service;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;

class DashboardService
{
    public function __construct(
        private OrderRepository $orderRepo,
        private ProductRepository $productRepo
    ) {}

    public function getStats(): array
    {
        return [
            'revenue' => $this->orderRepo->getTotalRevenue(),
            'pending' => $this->orderRepo->countPendingOrders(),
            'ordersCount' => $this->orderRepo->count([]),
            'rupture' => $this->productRepo->countOutOfStock(),
            'topProducts' => $this->productRepo->getTopSellingProducts(),
            'latestOrders' => $this->orderRepo->getLatestOrders(),
            'monthlySales' => $this->orderRepo->getMonthlySales(),
        ];
    }
}
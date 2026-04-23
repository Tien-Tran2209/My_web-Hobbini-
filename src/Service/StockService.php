<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class StockService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function update(Product $product, int $stock): void
    {
        $product->setStock(max(0, $stock));
        $this->em->flush();
    }
}
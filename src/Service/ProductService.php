<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function save(Product $product): void
    {
        if ($product->getPrice() < 0) {
            throw new \InvalidArgumentException('Price cannot be negative');
        }
        
        $this->em->persist($product);
        $this->em->flush();
    }

    public function delete(Product $product): void
    {
        $this->em->remove($product);
        $this->em->flush();
    }
}
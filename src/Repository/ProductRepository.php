<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function getTopSellingProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.sold', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function countOutOfStock(): int
    {
        return (int) $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.stock <= p.sold')
            ->getQuery()
            ->getSingleScalarResult();
    }
}

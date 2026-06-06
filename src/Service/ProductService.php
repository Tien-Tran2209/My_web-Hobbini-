<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductService
{
    /*public function __construct(
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
    }*/
    public function __construct(
        private EntityManagerInterface $em,
        private ProductRepository $productRepository,
        private CacheInterface $cache
    ) {}

    public function save(Product $product): void
    {
        if ($product->getPrice() < 0) {
            throw new \InvalidArgumentException('Price cannot be negative');
        }

        $this->em->persist($product);
        $this->em->flush();

        // clear cache
        $this->cache->delete('products_list');
    }

    public function delete(Product $product): void
    {
        $this->em->remove($product);
        $this->em->flush();

        // clear cache
        $this->cache->delete('products_list');
    }

    public function getProductsCached(): array
    {
        return $this->cache->get(

            'products_list',

            function (ItemInterface $item) {
                //dd('QUERY DATABASE');

                // cache 1 heure
                $item->expiresAfter(3600);

                return $this->productRepository
                    ->createQueryBuilder('p')
                    ->orderBy('p.id', 'DESC')
                    ->getQuery()
                    ->getResult();
            }
        );
    }
}
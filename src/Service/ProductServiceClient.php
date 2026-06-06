<?php

namespace App\Service;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductServiceClient
{
    /*public function __construct(
        private ProductRepository $productRepo,
        private CategoryRepository $categoryRepo,
        private PaginatorInterface $paginator
    ) {}

    public function getProductsWithFilter(Request $request): array
    {
        $categoryId = $request->query->get('category');
        $categories = $this->categoryRepo->findAll();

        $qb = $this->productRepo->createQueryBuilder('p');

        if ($categoryId) {
            $qb->where('p.category = :cat')
               ->setParameter('cat', $categoryId);
        }

        $qb->orderBy('p.id', 'DESC');

        $products = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            10
        );

        return [
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
        ];
    }*/
     public function __construct(
        private ProductRepository $productRepo,
        private CategoryRepository $categoryRepo,
        private PaginatorInterface $paginator,
        private ProductService $productService
    ) {}

    public function getProductsWithFilter(Request $request): array
    {
        $categoryId = $request->query->get('category');

        $categories = $this->categoryRepo->findAll();

        //REDIS CACHE
    
        $productsCached = $this->productService
            ->getProductsCached();

        //FILTER CATEGORY
        

        if ($categoryId) {

            $productsCached = array_filter(

                $productsCached,

                function ($product) use ($categoryId) {

                    return $product->getCategory()
                        && $product->getCategory()->getId() == $categoryId;
                }
            );
        }

        //PAGINATION
        
        $products = $this->paginator->paginate(

            $productsCached,

            $request->query->getInt('page', 1),

            10
        );

        return [

            'products' => $products,

            'categories' => $categories,

            'selectedCategory' => $categoryId,
        ];
    }
}
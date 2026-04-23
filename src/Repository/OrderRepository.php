<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    //    /**
    //     * @return Order[] Returns an array of Order objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Order
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

     public function getLastUserOrderNumber($user): int
    {
        $result = $this->createQueryBuilder('o')
            ->select('MAX(o.userOrderNumber)')
            ->where('o.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (int)$result : 0;
    }

    public function getTotalRevenue(): float
    {
        return (float) $this->createQueryBuilder('o')
            ->select('SUM(o.total_price)')
            ->where('o.status IN (:statuses)')
            ->setParameter('statuses', ['Validé', 'Expédié'])
            ->getQuery()
            ->getSingleScalarResult() ?: 0;
    }

    public function countPendingOrders(): int
    {
        return $this->count([
            'status' => 'En attente'
        ]);
    }

    public function getLatestOrders(int $limit = 5): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.created_at', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getMonthlySales(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT MONTH(created_at) as month,
                SUM(total_price) as total
            FROM `order`
            WHERE status IN ('Validé', 'Expédié')
            GROUP BY MONTH(created_at)
            ORDER BY month ASC
        ";

        return $conn->executeQuery($sql)->fetchAllAssociative();
    }
}

<?php

namespace App\Service;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class OrderManagerService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function updateStatus(Order $order, string $newStatus): void
    {
        $oldStatus = $order->getStatus();

        if ($oldStatus !== 'Expédié' && $newStatus === 'Expédié') {

            foreach ($order->getOrderItems() as $item) {

                $product = $item->getProduct();
                $qty = $item->getQuantity();

                if ($product->getRemaining() < $qty) {
                    throw new \Exception(
                        'Stock insuffisant pour ' . $product->getName()
                    );
                }

                $product->setSold(
                    $product->getSold() + $qty
                );
            }
        }

        if ($oldStatus === 'Expédié' && $newStatus !== 'Expédié') {

            foreach ($order->getOrderItems() as $item) {

                $product = $item->getProduct();
                $qty = $item->getQuantity();

                $product->setSold(
                    max(0, $product->getSold() - $qty)
                );
            }
        }

        if ($newStatus === 'Annulé') {
            $order->setCancelledBy('admin');
        }

        $order->setStatus($newStatus);

        $this->em->flush();
    }
}
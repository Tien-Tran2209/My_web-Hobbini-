<?php

namespace App\Tests\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Service\OrderManagerService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class OrderManagerServiceTest extends TestCase
{
    private EntityManagerInterface $em;
    private OrderManagerService $service;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);

        $this->service = new OrderManagerService($this->em);
    }

    public function testUpdateStatusToExpedieIncreaseSold(): void
    {
        $product = new Product();
        $product->setName('Ballon');
        $product->setStock(10);
        $product->setSold(2);

        $item = new OrderItem();
        $item->setProduct($product);
        $item->setQuantity(3);

        $order = new Order();
        $order->setStatus('Validé');
        $order->addOrderItem($item);

        $this->em
            ->expects($this->once())
            ->method('flush');

        $this->service->updateStatus($order, 'Expédié');

        $this->assertEquals(5, $product->getSold());
        
        $this->assertEquals('Expédié', $order->getStatus());
    }

    public function testUpdateStatusFromExpedieRollbackSold(): void
    {
        $product = new Product();
        $product->setName('Raquette');
        $product->setStock(20);
        $product->setSold(7);

        $item = new OrderItem();
        $item->setProduct($product);
        $item->setQuantity(4);

        $order = new Order();
        $order->setStatus('Expédié');
        $order->addOrderItem($item);

        $this->em
            ->expects($this->once())
            ->method('flush');

        $this->service->updateStatus($order, 'Annulé');

        $this->assertEquals(3, $product->getSold());
        $this->assertEquals('Annulé', $order->getStatus());
    }

    public function testUpdateStatusCannotMakeSoldNegative(): void
    {
        $product = new Product();
        $product->setName('Chaussure');
        $product->setStock(10);
        $product->setSold(1);

        $item = new OrderItem();
        $item->setProduct($product);
        $item->setQuantity(5);

        $order = new Order();
        $order->setStatus('Expédié');
        $order->addOrderItem($item);

        $this->em
            ->expects($this->once())
            ->method('flush');

        $this->service->updateStatus($order, 'Validé');

        $this->assertEquals(0, $product->getSold());
    }

   public function testUpdateStatusThrowExceptionIfStockInsufficient(): void
    {
        $product = new Product();
        $product->setName('Vélo');
        $product->setStock(5);
        $product->setSold(4); // remaining = 1

        $item = new OrderItem();
        $item->setProduct($product);
        $item->setQuantity(2);

        $order = new Order();
        $order->setStatus('Validé');
        $order->addOrderItem($item);

        $this->em
            ->expects($this->never())
            ->method('flush');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Stock insuffisant pour Vélo');

        $this->service->updateStatus($order, 'Expédié');
    }

    public function testCancelledByAdminIsSet(): void
    {
        $order = new Order();
        $order->setStatus('Validé');

        $this->em
            ->expects($this->once())
            ->method('flush');

        $this->service->updateStatus($order, 'Annulé');

        $this->assertEquals('Annulé', $order->getStatus());
        $this->assertEquals('admin', $order->getCancelledBy());
    }
}
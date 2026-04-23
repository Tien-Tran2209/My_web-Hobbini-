<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PaymentService
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderRepository $orderRepo
    ) {}

    public function handleStripeSuccess(UserInterface $user): void
    {
        $cart = $user->getCart();

        if (!$cart || $cart->getCartItems()->isEmpty()) {
            throw new \Exception("Votre panier est vide.");
        }

        $order = new Order();
        $order->setUser($user);
        $order->setStatus('Validé');
        $order->setPaymentStatus('paid');
        $order->setPaymentMethod('stripe');
        $order->setCreatedAt(new \DateTime());

        $lastNumber = $this->orderRepo->getLastUserOrderNumber($user);
        $order->setUserOrderNumber($lastNumber + 1);

        $totalPrice = 0;

        foreach ($cart->getCartItems() as $item) {

            $orderItem = new OrderItem();
            $orderItem->setOrderRef($order);
            $orderItem->setProduct($item->getProduct());
            $orderItem->setQuantity($item->getQuantity());
            $orderItem->setPrice($item->getProduct()->getPrice());

            $totalPrice += $item->getProduct()->getPrice() * $item->getQuantity();

            $this->em->persist($orderItem);
        }

        $order->setTotalPrice($totalPrice);

        $this->em->persist($order);

        foreach ($cart->getCartItems() as $item) {
            $this->em->remove($item);
        }

        $this->em->flush();
    }
}
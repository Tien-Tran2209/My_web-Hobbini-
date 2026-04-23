<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class OrderCheckoutService
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderRepository $orderRepo
    ) {}

    public function checkout(UserInterface $user, string $method): ?string
    {
        $cart = $user->getCart();

        if (!$cart || $cart->getCartItems()->isEmpty()) {
            throw new \Exception("Votre panier est vide.");
        }

        // CHECK STOCK
        foreach ($cart->getCartItems() as $cartItem) {
            if ($cartItem->getQuantity() > $cartItem->getProduct()->getRemaining()) {
                throw new \Exception("Stock insuffisant pour " . $cartItem->getProduct()->getName());
            }
        }

        /* =========================
           💵 CASH ON DELIVERY
        ==========================*/
        if ($method === 'cod') {

            $order = new Order();
            $order->setUser($user);
            $order->setStatus('En attente');
            $order->setPaymentStatus('pending');
            $order->setPaymentMethod('cod');
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

            return null;
        }

        /* =========================
           💳 STRIPE PAYMENT
        ==========================*/
        if ($method === 'stripe') {

            Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

            $totalPrice = 0;

            foreach ($cart->getCartItems() as $item) {
                $totalPrice += $item->getProduct()->getPrice() * $item->getQuantity();
            }

            $session = Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Commande utilisateur',
                        ],
                        'unit_amount' => $totalPrice * 100,
                    ],
                    'quantity' => 1,
                ]],
                'success_url' => $_ENV['YOUR_DOMAIN'] . '/payment/success',
                'cancel_url' => $_ENV['YOUR_DOMAIN'] . '/payment/cancel',
            ]);

            return $session->url;
        }

        return null;
    }
}